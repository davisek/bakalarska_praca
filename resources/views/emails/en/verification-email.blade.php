<!DOCTYPE html>
<html>
    <head>
        <title>Verification Email</title>
    </head>
    <body>
        <h1>Hello {{ $fullName }}</h1>
        <p>Please verify your email by clicking the link below:</p>
        <a href="{{ url('/verify-email?hash=' . $hash) }}">Verify Email</a>
        <p>Your verification code is: {{ $verificationCode }}</p>
    </body>
</html>
