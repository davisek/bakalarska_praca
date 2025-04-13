<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sensor Alert</title>
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

    <h1 style="margin-bottom: 20px;">Hello {{ $user->name }} {{ $user->surname }}</h1>

    <p style="font-size: 16px;">
        The sensor <strong>{{ $measurement->sensor->display_name }}</strong> has reported a significant change in value.
    </p>

    <p style="font-size: 16px; margin-top: 20px;">
        <strong style="margin-bottom: 20px;">New value:</strong> {{ $measurement->value }} {{ $measurement->sensor->unit_of_measurement }}<br>
        <strong style="margin-bottom: 20px;">Change:</strong> {{ number_format($percentageChange, 2) }}%<br>
        <strong>Time:</strong> {{ $measurement->created_at->format('Y-m-d H:i:s') }}
    </p>

</div>
</body>
</html>
