<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password</title>
    <style>
        body { font-family: Georgia, serif; background: linear-gradient(135deg, #f4efe6, #dfe8f1); color: #1f2933; margin: 0; }
        .wrap { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }
        .card { width: min(420px, 100%); background: rgba(255,255,255,.96); border: 1px solid rgba(31,41,51,.1); border-radius: 18px; box-shadow: 0 20px 60px rgba(31,41,51,.12); padding: 32px; }
        input { width: 100%; box-sizing: border-box; padding: 12px 14px; border: 1px solid #cbd2d9; border-radius: 10px; margin: 12px 0 16px; font-size: 1rem; }
        button { width: 100%; background: #1f2933; color: #fff; border: 0; border-radius: 10px; padding: 12px 14px; font-size: 1rem; cursor: pointer; }
        .alert { background: #edfdf3; color: #166534; border: 1px solid #bbf7d0; border-radius: 10px; padding: 12px 14px; margin-bottom: 16px; }
        a { color: #1d4ed8; text-decoration: none; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <h1>Reset password</h1>
            <p>Enter your account email and Laravel will send a reset link.</p>

            @if (session('status'))
                <div class="alert">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                <button type="submit">Send reset link</button>
            </form>

            <p style="margin-top:16px;"><a href="{{ route('login') }}">Back to login</a></p>
        </div>
    </div>
</body>
</html>
