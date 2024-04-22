<!DOCTYPE html>
<html lang="en">
<body>
    <img src="{{ asset("assets/img/logo-secondary-email.svg") }}" alt="DreamForLyf International" width="100">
    <br>
    <p>Dear {{ $name }},</p>
    <br>
    <p>You requested a withdrawal amount of ${{ number_format($amount, 2) }}. We want you to know we are currently processing it and will be paid on Friday as scheduled</p>
    <br>
    <p>We hope you initiated this transaction, if you didn't we advice you ignore this message and give us a call to erase this withdrawal with id #{{ $id }}</p>
    <br>
    <p>Thank you for joining our big family as we strive to grow together</p>
    <br><br>
    <p>Best regards,</p>
    <p>DreamForLyf Team</p>
</body>
</html>
