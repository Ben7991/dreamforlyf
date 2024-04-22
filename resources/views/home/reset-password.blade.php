<x-layout.auth>
    <div class="py-2 mb-5 mb-lg-3 mb-xl-4 mb-xxl-5">
        <div class="d-flex algin-items-center justify-content-between mb-3">
            <h5 class="m-0">{{ __("reset_password") }}</h5>
            <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}">{{ __("home") }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __("reset_password") }}</li>
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

        <form action="/{{App::getLocale()}}/reset-password?token={{$token}}&email={{ $email }}" method="POST" id="form">
            @csrf

            <div class="form-group mb-3">
                <label for="new_password">{{ __("new_password") }}</label>
                <input type="password" id="new_password" name="new_password" class="form-control">
                <small class="text-danger d-none"></small>
            </div>
            <div class="form-group mb-4">
                <label for="confirm_password">{{ __("confirm_password") }}</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                <small class="text-danger d-none"></small>
            </div>

            <div class="d-flex algin-items-center justify-content-between">
                <button class="btn btn-success btn-submit" type="submit">
                    <span class="main-btn">
                        <i class="bi bi-send"></i> {{ __("submit") }}
                    </span>
                    <span class="loader d-none">
                        <div class="d-flex align-items-center gap-1">
                            <div class="spinner-border spinner-border-sm" role="status" id="loader">
                                <span class="visually-hidden">{{ __("loading") }}</span>
                            </div>
                            {{ __("loading") }}
                        </div>
                    </span>
                </button>
            </div>
        </form>
    </div>

    @push("scripts")
        <script src="{{ asset("assets/js/reset-password.js")  }}"></script>
    @endpush
</x-layout.auth>
