<!DOCTYPE html>
<html lang="en">
<body>
    <img src="{{ asset("assets/img/logo-secondary-email.svg") }}" alt="DreamForLyf International" width="100">
    <br>
    <p>Dear {{ $name }},</p>
    <br>
    <p>Your account has successfully being created and below are your login credentials</p>
    <br>
    <p>Email: {{ $email }}</p>
    <p>Password: {{ $password }}</p>
    <br>
    <p>To log into your account, visit our login page: <a href="http://localhost:8080/fr/login">localhost:8080/fr/login</a></p>
    <p>Once you are logged in we advice that you reset your password to something you can easily remember and please do not expose your credentials to other users of the system</p>
    <br>
    <p>Thank you for joining our big family as we strive to grow together</p>
    <br><br>
    <p>Best regards,</p>
    <p>DreamForLyf Team</p>
</body>
</html>
