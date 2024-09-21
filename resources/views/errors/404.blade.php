<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Page Not Found</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz,wght@6..12,300;6..12,400;6..12,600;6..12,700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset("assets/img/logo-secondary.png") }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset("assets/css/base.css") }}">
    <style>
        body {
            font-family: "Nunito Sans", sans-serif;
        }

        h1, h5 { font-weight: 900; }

        .main {
            width: 100%;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <main class="main">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 text-center col-md-6 col-xl-5 col-xxl-4">
                    <h1 class="display-4">Oops!!</h1>
                    <h5>Page Not Found</h5>
                    <p class="text-secondary">The page you are trying to access doesn't exist. Click on the button below to navigate back to the home previous page.</p>
                    <button class="btn btn-main" onclick="window.history.back()">Go back</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
