<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Login OTP</title>
</head>

<body>
    <p>Hello, {{ $name }}</p>

    <p>Your One-Time Password (OTP) is:</p>

    <h2 style="color:#2c3e50; font-size:24px;">{{ $otp }}</h2>

    <p>This OTP is valid for <strong>5 minutes</strong>.
        Please do not share it with anyone.</p>

    <p>If you did not request this login, please ignore this email.</p>

    <p>Thank you,<br>
        {{ config('app.name') }}</p>
</body>

</html>
