<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Team Invite</title>
</head>
<body>
    <h2>Hello {{ $user->name }},</h2>

    <p>You’ve been added to the team at {{ config('app.name') }}!</p>

    <p>You can log in to your account using your email and password that was shared with you.</p>

    <p>If you have any issues, feel free to reach out.</p>

    <p>Thanks,<br>{{ config('app.name') }} Team</p>
</body>
</html>
