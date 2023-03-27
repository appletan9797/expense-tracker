<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <h1>Reset Your Password</h1>

    <p>Dear user,</p>

    <p>We have received a request to reset your password. To reset your password, please click the link below:</p>

    <a href="{{ url('reset-password/' . $token) }}">Reset Password</a>

    <p>If you did not request this change, please ignore this email and your password will remain unchanged.</p>

    <p>Thank you,</p>
    <p>The Team</p>
</body>
</html>
