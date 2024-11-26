<!DOCTYPE html>
<html lang="{{ App::currentLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __("title_copyright") }}</title>
    <link rel="shortcut icon" href="{{ asset("assets/img/logo-secondary.png") }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset("assets/css/base.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/css/home.css") }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz,wght@6..12,300;6..12,400;6..12,600;6..12,700&display=swap" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .social-links {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 1.2em;
            color: white;
        }

        .social-links:nth-child(1):hover {
            color: red;
        }

        .social-links:nth-child(2):hover {
            color: blue;
        }

        .social-links:nth-child(3):hover {
            color: black;
        }
    </style>
</head>
<body>
    <div class="backdrop"></div>
    <nav class="navigation py-3 py-xxl-4 border-bottom shadow-sm">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="/{{ App::currentLocale() }}" class="navigation-brand">
                <img src="{{ asset("assets/img/logo-secondary.png") }}" alt="DreamForLyf Intl. Logo" class="navigation-brand-logo">
            </a>
            <div class="navigation-collapse">
                <ul class="navigation-nav">
                    <li>
                        <a href="/{{ App::currentLocale() }}/" class="navigation-nav-link {{ $title == "Home" ? 'active' : "" }}">
                            {{ __("home") }}
                        </a>
                    </li>
                    <li>
                        <a href="/{{ App::currentLocale() }}/about-us" class="navigation-nav-link {{ $title == "About" ? 'active' : "" }}">
                            {{ __("about_us") }}
                        </a>
                    </li>
                    <li>
                        <a href="/{{ App::currentLocale() }}/products" class="navigation-nav-link {{ $title == "Products" ? 'active' : "" }}">
                            {{ __("products") }}
                        </a>
                    </li>
                    <li>
                        <a href="/{{ App::currentLocale() }}/opportunity" class="navigation-nav-link {{ $title == "Opportunity" ? 'active' : "" }}">
                            {{ __("opportunity") }}
                        </a>
                    </li>
                    <li>
                        <a href="/{{ App::currentLocale() }}/faqs" class="navigation-nav-link {{ $title == "FAQs" ? 'active' : "" }}">
                            {{ __("faqs") }}
                        </a>
                    </li>
                    <li>
                        <a href="/{{ App::currentLocale() }}/contact-us" class="navigation-nav-link {{ $title == "Contact" ? 'active' : "" }}">
                            {{ __("contact_us") }}
                        </a>
                    </li>
                </ul>
                <div class="d-flex flex-column flex-xl-row gap-2">
                    <a href="/{{ App::currentLocale() }}/login" class="navigation-nav-link auth">{{ __("sign_in") }}</a>
                    <div class="d-none d-xl-block">
                        <x-internalize-dropdown />
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 d-xl-none">
                <x-internalize-dropdown />
                <button class="navigation-hamburger shadow">
                    <span class="navigation-hamburger-bar"></span>
                    <span class="navigation-hamburger-bar"></span>
                </button>
            </div>
        </div>
    </nav>


    <main>
        {{ $slot }}

        <div class="chatty">
            <a href="https://wa.me/+2250100483050?text=Hi" class="chatty-link shadow" target="_blank">
                <img src="{{ asset("assets/img/whatsapp.png") }}" alt="Whatsapp link">
                <span class="chatty-tooltip">
                    Don't hesitate to say Hi! <i class="bi bi-hand-thumbs-up"></i>
                </span>
            </a>
        </div>
    </main>

    <footer>
        <div class="footer">
            <div class="container">
                <div class="row flex-wrap justify-content-center">
                    <div class="col-12 col-md-6 col-xl-4 text-center">
                        <p class="m-0 text-center">&copy; {{ __("footer_copyright") }}</p>
                    </div>
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="d-flex gap-2 align-items-center justify-content-center">
                            <a href="https://www.youtube.com/channel/UCJ-klU3Nlajqlvhte24DZ2g" class="social-links" target="_blank">
                                <i class="bi bi-youtube"></i>
                            </a>
                            <a href="https://www.facebook.com/profile.php?id=61567150461547&mibextid=ZbWKwL" target="_blank" class="social-links">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="https://www.tiktok.com/@user2040204462944" class="social-links" target="_blank">
                                <i class="bi bi-tiktok"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-4 text-center">
                        <a class="text-primary d-flex align-items-center justify-content-center text-decoration-none gap-2" href="mailto: info@dreamforlyfintl.com">
                            <i class="bi bi-envelope"></i> info@dreamforlyfintl.com
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="{{ asset("assets/js/general.js") }}"></script>
    <script src="{{ asset("assets/js/home.js") }}"></script>
    @stack("scripts")
</body>
</html>
