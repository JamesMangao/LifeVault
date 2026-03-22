<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Mail;
use App\Mail\MentionNotification;

class NotificationController extends Controller
{
    /**
     * Send email notification for a mention
     */
    public function sendMentionNotification(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'mentioner_name' => 'required|string',
            'content_preview' => 'required|string',
            'content_type' => 'required|string', // 'post' or 'comment'
            'url' => 'nullable|string',
        ]);

        try {
            $projectId = config('services.firebase.project_id', env('VITE_FIREBASE_PROJECT_ID', 'lifevault-77666'));
            $accessToken = $this->getGoogleAccessToken();

            if (!$accessToken) {
                throw new \Exception('Could not generate Google Access Token');
            }

            // Query Firestore via REST API
            // Ref: https://firebase.google.com/docs/firestore/reference/rest/v1/projects.databases.documents/runQuery
            $response = \Illuminate\Support\Facades\Http::withToken($accessToken)
                ->post("https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents:runQuery", [
                    'structuredQuery' => [
                        'from' => [['collectionId' => 'users']],
                        'where' => [
                            'fieldFilter' => [
                                'field' => ['fieldPath' => 'username'],
                                'op' => 'EQUAL',
                                'value' => ['stringValue' => strtolower($validated['username'])]
                            ]
                        ],
                        'limit' => 1
                    ]
                ]);

            if ($response->failed()) {
                throw new \Exception('Firestore REST query failed: ' . $response->body());
            }

            $results = $response->json();
            
            // runQuery returns an array of objects, first one might be empty if no match
            if (empty($results) || !isset($results[0]['document'])) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $doc = $results[0]['document'];
            $fields = $doc['fields'] ?? [];
            
            // Firestore REST format for strings: { "stringValue": "..." }
            $email = $fields['email']['stringValue'] ?? null;
            $displayName = $fields['displayName']['stringValue'] ?? ($fields['name']['stringValue'] ?? 'User');

            if ($email) {
                $userData = [
                    'email' => $email,
                    'displayName' => $displayName,
                    'username' => $validated['username']
                ];

                Mail::to($email)->send(new MentionNotification($validated, $userData));
                
                \Illuminate\Support\Facades\Log::info("[NotificationController] Mention email sent to {$email}");
                return response()->json(['message' => 'Notification sent successfully']);
            }

            return response()->json(['message' => 'User email not found in Firestore'], 404);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('[NotificationController] Mention failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Failed to send notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate Google OAuth2 Access Token using Service Account JWT
     */
    private function getGoogleAccessToken()
    {
        $path = base_path(env('FIREBASE_CREDENTIALS', 'storage/app/firebase-service-account.json'));
        if (!file_exists($path)) {
            \Illuminate\Support\Facades\Log::error("[NotificationController] Service account file not found at: {$path}");
            return null;
        }

        $serviceAccount = json_decode(file_get_contents($path), true);
        $privateKey = $serviceAccount['private_key'];
        $clientEmail = $serviceAccount['client_email'];

        $now = time();
        $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
        $payload = json_encode([
            'iss' => $clientEmail,
            'scope' => 'https://www.googleapis.com/auth/datastore https://www.googleapis.com/auth/userinfo.email',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now
        ]);

        $base64UrlHeader = $this->base64UrlEncode($header);
        $base64UrlPayload = $this->base64UrlEncode($payload);

        $signature = '';
        openssl_sign($base64UrlHeader . "." . $base64UrlPayload, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        $base64UrlSignature = $this->base64UrlEncode($signature);

        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        $response = \Illuminate\Support\Facades\Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt
        ]);

        if ($response->failed()) {
            \Illuminate\Support\Facades\Log::error("[NotificationController] OAuth token request failed: " . $response->body());
            return null;
        }

        return $response->json('access_token');
    }

    private function base64UrlEncode($data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }
}
