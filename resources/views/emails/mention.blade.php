<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Syne', sans-serif;
            background-color: #0b0f1a;
            color: #e8eaf0;
            margin: 0;
            padding: 40px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: linear-gradient(145deg, #111827, #1a2235);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 40px 100px rgba(0,0,0,0.5);
        }
        .logo {
            font-size: 24px;
            font-weight: 800;
            color: #4f8ef7;
            margin-bottom: 24px;
            text-align: center;
        }
        .content {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 32px;
        }
        .preview {
            background: rgba(255,255,255,0.03);
            border-left: 4px solid #a78bfa;
            padding: 20px;
            border-radius: 12px;
            font-style: italic;
            margin-bottom: 32px;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #7c3aed, #4f8ef7);
            color: #ffffff;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 99px;
            font-weight: 700;
            text-align: center;
        }
        .footer {
            font-size: 12px;
            color: #6b7a99;
            text-align: center;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">LifeVault</div>
        <div class="content">
            <p>Hi {{ $user['displayName'] ?? 'there' }},</p>
            <p><strong>{{ $data['mentioner_name'] }}</strong> mentioned you in a {{ $data['content_type'] }}:</p>
        </div>
        <div class="preview">
            "{{ $data['content_preview'] }}"
        </div>
        <div style="text-align: center;">
            <a href="{{ url('/explore') }}" class="btn">View on LifeVault</a>
        </div>
        <div class="footer">
            You received this because someone mentioned you on LifeVault.<br>
            &copy; {{ date('Y') }} LifeVault — Your Personal Space.
        </div>
    </div>
</body>
</html>
