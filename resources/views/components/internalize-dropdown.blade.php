<div class="dropdown">
    <button class="btn btn-outline-primary dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        @if (App::isLocale("en"))
            <img src="{{ asset("assets/img/english.png") }}" alt="{{ __("trans_en") }}" class="main-lang-icon"> {{ __("trans_en") }}
        @else
            <img src="{{ asset("assets/img/french.png") }}" alt="{{ __("trans_fr") }}" class="main-lang-icon"> {{ __("trans_fr") }}
        @endif
    </button>
    <ul class="dropdown-menu">
        <li class="{{ App::isLocale("en") ? "d-none" : "" }}">
            <a class="dropdown-item d-flex align-items-center gap-2"
                href="{{ str_replace(App::currentLocale(), 'en', url()->full()) }}">
                <img src="{{ asset("assets/img/english.png") }}" alt="{{ __("trans_en") }}" class="main-lang-icon"> {{ __("trans_en") }}
            </a>
        </li>
        <li class="{{ App::isLocale("fr") ? "d-none" : "" }}">
            <a class="dropdown-item d-flex align-items-center gap-2"
                href="{{ str_replace(App::currentLocale(), 'fr', url()->full()) }}">
                <img src="{{ asset("assets/img/french.png") }}" alt="{{ __("trans_fr") }}" class="main-lang-icon"> {{ __("trans_fr") }}
            </a>
        </li>
    </ul>
</div>
