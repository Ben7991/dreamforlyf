<!DOCTYPE html>
<html lang="en">
<body>
    <img src="{{ asset("assets/img/logo-secondary-email.svg") }}" alt="DreamForLyf International" width="100">
    <p>Dear {{ $name }},</p>
    <p>Your withdrawal pin has been reset by admin as requested. Below is your new pin to use during withdrawal</p>
    <h1>{{ $pin }}</h1>
    <p>If you didn't request this process, we are so sorry and advice that you use the pin above. Please don't share due to security reasons</p>
    <br>
    <p>Best regards,</p>
    <p>DreamForLyf International Team</p>
</body>
</html>
