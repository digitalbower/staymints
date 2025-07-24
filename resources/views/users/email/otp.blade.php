<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h2>Hello!</h2>
    <p>Your One-Time Password (OTP) is:</p>
    <h3 style="color: #2d3748;">{{ $otp }}</h3>
    <p>This code will expire in 10 minutes.</p>
    <p>If you didn’t request this, you can safely ignore it.</p>
    <br>
    <p>Thanks,<br>The {{ config('app.name') }} Team</p>
</body>
</html>