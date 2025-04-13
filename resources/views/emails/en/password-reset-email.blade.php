<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Forgotten Password</title>
</head>
<body style="
    margin: 0;
    padding: 0;
    min-height: 100vh;
    background: linear-gradient(138deg, rgba(17,24,39,1) 0%, rgba(21,26,47,1) 53%, rgba(29,30,60,1) 79%, rgba(38,35,76,1) 100%) no-repeat;
    background-size: cover;
    color: #ffffff;
">

<div style="max-width: 600px; margin: 0 auto; padding: 40px 30px; text-align: center;">

    <h1 style="margin-bottom: 20px;">Hello {{ $fullName }}</h1>

    <p style="font-size: 16px;">
        Your code for password reset is:
    </p>

    <p style="font-size: 28px; font-weight: bold; margin-top: 10px; background: #1f2937; display: inline-block; padding: 10px 20px; border-radius: 6px;">
        {{ $code }}
    </p>

    <p style="font-size: 14px; margin-top: 30px;">
        If you didn't request a password reset, you can safely ignore this email.
    </p>

</div>
</body>
</html>
