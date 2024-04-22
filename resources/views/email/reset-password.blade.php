<!DOCTYPE html>
<html lang="en">
<body>
    <img src="{{ asset("assets/img/logo-secondary-email.svg") }}" alt="DreamForLyf International" width="100">
    <br>
    <p>Dear {{ $name }},</p>
    <br>
    <p>You requested a reset of your password with this {{ $email }}. Below is a link to access the reset password page to reset your account</p>
    <br>
    <a href="http://localhost:8080/{{App::currentLocale()}}/reset-password?token={{ $token }}&email={{ $email }}">Go to reset password page now</a>
    <br>
    <p>If you didn't initial this process, we advice you ignore this email and we apologize for any inconveniences caused thank you</p>
    <br>
    <p>Thank you for joining our big family as we strive to grow together</p>
    <br><br>
    <p>Best regards,</p>
    <p>DreamForLyf Team</p>
</body>
</html>
