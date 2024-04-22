<x-layout.auth>
    <div class="py-2 mb-5 mb-lg-3 mb-xl-4 mb-xxl-5">
        <div class="d-flex algin-items-center justify-content-between mb-3">
            <h5 class="m-0">{{ __("forgot_password") }}</h5>
            <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}">{{ __("home") }}</a></li>
                    <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/login">{{ __("login") }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __("forgot_password") }}</li>
                </ol>
            </nav>
        </div>

        @if($errors->all())
            <ul>
                @foreach ($errors->all() as $message)
                    <li class="text-danger">{{ $message }}</li>
                @endforeach
            </ul>
        @endif

        <form action="/{{App::getLocale()}}/forgot-password" method="POST" id="form">
            @csrf

            <div class="form-group mb-3">
                <label for="email">{{ __("email") }}</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="{{ __("example_placeholder") }}">
                <small class="text-danger d-none"></small>
            </div>
            <div class="d-flex algin-items-center justify-content-between">
                <button class="btn btn-main" type="submit">
                    <i class="bi bi-send"></i> {{ __("submit") }}
                </button>
                <div class="spinner-border text-primary d-none" role="status" id="loader">
                    <span class="visually-hidden">{{ __("loading") }}</span>
                </div>
            </div>
        </form>
    </div>

    @push("scripts")
        <script src="{{ asset("assets/js/forgot-password.js")  }}"></script>
    @endpush
</x-layout.auth>
