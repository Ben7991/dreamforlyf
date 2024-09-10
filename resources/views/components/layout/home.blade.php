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
    </main>

    <footer>
        <div class="footer">
            <div class="container">
                <p class="m-0 text-center">&copy; {{ __("footer_copyright") }}</p>
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
