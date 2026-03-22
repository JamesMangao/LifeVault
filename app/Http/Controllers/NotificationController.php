<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Mail;
use App\Mail\MentionNotification;

class NotificationController extends Controller
{
    protected $database;
    protected $firestore;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')));
        $this->firestore = $factory->createFirestore();
    }

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
            // Find user in Firestore by username
            $usersRef = $this->firestore->database()->collection('users');
            $query = $usersRef->where('username', '==', strtolower($validated['username']));
            $documents = $query->documents();

            if ($documents->isEmpty()) {
                return response()->json(['message' => 'User not found'], 404);
            }

            foreach ($documents as $document) {
                if ($document->exists()) {
                    $userData = $document->data();
                    $email = $userData['email'] ?? null;

                    if ($email) {
                        Mail::to($email)->send(new MentionNotification($validated, $userData));
                        return response()->json(['message' => 'Notification sent successfully']);
                    }
                }
            }

            return response()->json(['message' => 'User email not found'], 404);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to send notification: ' . $e->getMessage()], 500);
        }
    }
}
