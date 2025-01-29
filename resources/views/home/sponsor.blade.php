<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DreamForLyf International</title>
    <link rel="shortcut icon" href="{{ asset('assets/img/logo-main.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sponsor.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz,wght@6..12,300;6..12,400;6..12,600;6..12,700&display=swap"
        rel="stylesheet">
</head>

<body>
    <x-alert />

    <main>
        <nav class="navbar bg-body-tertiary">
            <div class="container d-flex align-items-center justify-content-between">
                <a class="navbar-brand" href="/{{ App::currentLocale() }}">
                    <img src="{{ asset('assets/img/logo-secondary.png') }}" alt="Bootstrap" width="50">
                </a>
                <x-internalize-dropdown />
            </div>
        </nav>
        <section class="hero py-3 py-xl-5">
            <div class="container h-100">
                <div
                    class="hero-jumbotron h-100 rounded p-3 p-xxl-4 d-flex align-items-center justify-content-center flex-column">
                    <h3>{{ __('register_now') }}</h3>
                    <p class="text-secondary">{{ __('register_description') }}</p>
                </div>
            </div>
        </section>
        <section class="py-3 py-xxl-5">
            <div class="container">
                <div class="d-block d-md-flex align-items-center justify-content-between mb-3 mb-xxl-4">
                    <h4 class="mb-2 mb-md-0">{{ __('your_details') }}</h4>
                    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
                        aria-label="breadcrumb">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}">{{ __('home') }}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('sponsor') }}</li>
                        </ol>
                    </nav>
                </div>
                <p>{{ __('sponsor_notice') }}</p>
                <ul>
                    <li>{{ __('sponsor_notice_1') }}</li>
                    <li>{{ __('sponsor_notice_2') }}</li>
                </ul>

                <ul>
                    @foreach ($errors->all() as $message)
                        <li class="text-danger">{{ $message }}</li>
                    @endforeach
                </ul>

                <form
                    action="/{{ App::currentLocale() }}/sponsor/register?id={{ $id }}&token={{ $token }}&side={{ $side }}"
                    method="POST" id="form">
                    @csrf
                    <div class="row mb-3 mb-xxl-4">
                        <div class="col-12 col-md-4 col-xxl-3 mb-3">
                            <label for="sponsor" class="text-secondary">{{ __('sponsor') }}</label>
                            <input type="text" id="sponsor" class="form-control" value="{{ $sponsor->name }}"
                                readonly>
                            <small class="text-danger d-none"></small>
                        </div>
                        <div class="col-12 col-md-4 col-xxl-3 mb-3">
                            <label for="name" class="text-secondary">{{ __('name') }}</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <small class="text-danger d-none"></small>
                        </div>
                        <div class="col-12 col-md-4 col-xxl-3 mb-3">
                            <label for="email" class="text-secondary">{{ __('email') }}</label>
                            <input type="email" name="email" id="email" class="form-control">
                            <small class="text-danger d-none"></small>
                        </div>
                        <div class="col-12 col-md-4 col-xxl-3 mb-3">
                            <label for="country" class="text-secondary">{{ __('country') }}</label>
                            <select name="country" id="country" class="form-select">
                                <option value="">{{ __('select_country') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->name }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-danger d-none"></small>
                        </div>
                        <div class="col-12 col-md-4 col-xxl-3 mb-3">
                            <label for="city" class="text-secondary">{{ __('city') }}</label>
                            <input type="text" name="city" id="city" class="form-control">
                            <small class="text-danger d-none"></small>
                        </div>
                        <div class="col-12 col-md-4 col-xxl-3 mb-3">
                            <label for="phone_number" class="text-secondary">{{ __('phone_number') }}</label>
                            <input type="text" name="phone_number" id="phone_number" class="form-control">
                            <small class="text-danger d-none"></small>
                        </div>
                        <div class="col-12 col-md-4 col-xxl-3 mb-3">
                            <label for="wave" class="text-secondary">{{ __('wave_number') }}</label>
                            <input type="text" name="wave" id="wave" class="form-control">
                            <small class="text-danger d-none"></small>
                        </div>
                        <div class="col-12 col-md-4 col-xxl-3 mb-3">
                            <label for="package_id" class="text-secondary">{{ __('membership_package') }}</label>
                            <select name="package_id" id="package_id" class="form-select">
                                <option value="">{{ __('select_membership_package') }}</option>
                                @foreach ($registrationPackages as $registrationPackage)
                                    <option value="{{ $registrationPackage->id }}">{{ $registrationPackage->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-danger d-none"></small>
                        </div>
                        <div class="col-12 col-md-4 col-xxl-3 mb-3">
                            <label for="stockist_id" class="text-secondary">{{ __('select_stockist') }}</label>
                            <select name="stockist_id" id="stockist_id" class="form-select">
                                <option value="">{{ __('select_stockist') }}</option>
                                @foreach ($stockists as $stockist)
                                    <option value="{{ $stockist->id }}">{{ $stockist->code }}</option>
                                @endforeach
                            </select>
                            <small class="text-danger d-none"></small>
                        </div>
                    </div>
                    <div class="container">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="m-0">{{ __('select_package_type') }}</h5>
                            <div class="spinner-border text-primary d-none" role="status" id="package-spinner">
                                <span class="visually-hidden">{{ __('loading') }}</span>
                            </div>
                        </div>
                        <div class="row mb-4" id="package-type-holder">
                            {{-- <div class="col-12 col-md-4 col-xl-3 col-xxl-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                    <div class="d-flex flex-column">
                                        <label class="form-check-label text-secondary mb-2" for="flexRadioDefault1">
                                            Type A
                                        </label>
                                        <img src="{{ asset("assets/img/starter-b.png") }}" alt="" class="img-fluid img-thumbnail">
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                        <button class="btn btn-success btn-submit">
                            <span class="main-btn">
                                <i class="bi bi-save"></i> {{ __('save') }}
                            </span>
                            <x-submit-spinner />
                        </button>
                        <p class="my-3">
                            <i class="bi bi-info-circle"></i> {{ __('registration_btn_action') }}
                        </p>
                    </div>
                </form>
            </div>
        </section>
        <div class="container">
            <hr>
        </div>
        <footer class="py-3 py-xxl-4">
            <p class="text-secondary text-center">DreamForLyf {{ __('international') }} &copy; 2024 |
                {{ __('all_rights_reserved') }}</p>
        </footer>
    </main>

    <div class="img-modal">
        <div class="img-modal-container">
            <button class="btn img-modal-btn">
                <i class="bi bi-x fs-3"></i>
            </button>
            <img src="{{ asset('assets/img/starter-b.png') }}" class="img-modal-image">
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ asset('assets/js/general.js') }}"></script>
    <script src="{{ asset('assets/js/sponsor.js') }}"></script>
</body>

</html>
