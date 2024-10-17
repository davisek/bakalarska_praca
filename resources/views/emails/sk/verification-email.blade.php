<!DOCTYPE html>
<html>
    <head>
        <title>Overovací Email</title>
    </head>
    <body>
        <h1>Ahoj {{ $fullName }}</h1>
        <p>Prosím, overte svoju emailovú adresu kliknutím na odkaz nižšie:</p>
        <a href="{{ url('/verify-email?hash=' . $hash) }}">Overiť Email</a>
        <p>Tvoj overovací kód je: {{ $verificationCode }}</p>
    </body>
</html>
