<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Choose New Password</title>
    <style>
        body { font-family: Georgia, serif; background: linear-gradient(135deg, #f4efe6, #dfe8f1); color: #1f2933; margin: 0; }
        .wrap { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }
        .card { width: min(420px, 100%); background: rgba(255,255,255,.96); border: 1px solid rgba(31,41,51,.1); border-radius: 18px; box-shadow: 0 20px 60px rgba(31,41,51,.12); padding: 32px; }
        input { width: 100%; box-sizing: border-box; padding: 12px 14px; border: 1px solid #cbd2d9; border-radius: 10px; margin: 12px 0 16px; font-size: 1rem; }
        button { width: 100%; background: #1f2933; color: #fff; border: 0; border-radius: 10px; padding: 12px 14px; font-size: 1rem; cursor: pointer; }
        .alert { background: #fdecea; color: #9b1c1c; border: 1px solid #f5c2c7; border-radius: 10px; padding: 12px 14px; margin-bottom: 16px; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <h1>Choose a new password</h1>

            @if ($errors->any())
                <div class="alert">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autocomplete="username">

                <label for="password">New password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password">

                <label for="password_confirmation">Confirm password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">

                <button type="submit">Reset password</button>
            </form>
        </div>
    </div>
</body>
</html>
