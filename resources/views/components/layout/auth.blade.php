<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DreamForLyf International</title>
    <link rel="shortcut icon" href="{{ asset("assets/img/logo-secondary.png") }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset("assets/css/auth.css") }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz,wght@6..12,300;6..12,400;6..12,600;6..12,700&display=swap" rel="stylesheet">
</head>
<body>
    <main class="main">
        <div class="container main-container overflow-hidden">
            <x-alert />
            <div class="row">
                <div class="col-12 col-lg-6 d-none d-lg-block main-left">
                    <img src="{{ asset("assets/img/login.svg") }}" alt="{{ __("login") }} Image" class="main-img-right">
                </div>
                <div class="col-12 col-lg-6 p-md-4 p-xl-4 p-xxl-5">
                    <div class="w-100 d-flex align-items-center justify-content-between py-3 p-md-0">
                        <a class="d-flex align-items-center main-brand gap-2" href="/{{ App::currentLocale() }}">
                            <img src="{{ asset("assets/img/logo-secondary.png") }}" class="main-logo">
                        </a>
                        <x-internalize-dropdown />
                    </div>
                    <div class="text-center py-3">
                        <span class="main-icon border shadow">
                            <i class="bi bi-person"></i>
                        </span>
                    </div>
                    {{ $slot }}
                    <hr>
                    <p class="text-center m-0">&copy; {{ __("footer_copyright") }}</p>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack("scripts")
</body>
</html>
