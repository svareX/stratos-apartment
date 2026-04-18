<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    @if(config('services.google.site_verification'))
        <meta name="google-site-verification" content="{{ config('services.google.site_verification') }}">
    @endif
    <title>Enter Website Password</title>
    <link rel="stylesheet" href="/css/app.css">
    <style>
        body { display:flex; align-items:center; justify-content:center; min-height:100vh; font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; }
        .card { width:360px; padding:24px; border-radius:8px; box-shadow:0 6px 24px rgba(0,0,0,.08); }
        .field { margin-bottom:12px; }
    </style>
</head>
<body>
    <div class="card">
        <h2 style="margin:0 0 12px 0">Protected site</h2>

        @if($errors->any())
            <div style="color:#b00020;margin-bottom:12px">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.submit') }}">
            @csrf
            <div class="field">
                <label for="website_password">Password</label>
                <input id="website_password" name="website_password" type="password" autocomplete="current-password" required style="width:100%;padding:8px;margin-top:6px;">
            </div>

            <div>
                <button type="submit" style="padding:10px 16px">Enter</button>
            </div>
        </form>
    </div>
</body>
</html>
