<x-layout.distributor>
    <x-slot name="title">Profile</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-2 mb-md-0">{{ __("profile") }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("profile") }}</li>
            </ol>
          </nav>
    </div>

    <div class="container-fluid p-0">
        <div class="row mb-3 mb-xxl-4">
            <div class="col-12">
                <div class="d-flex gap-3 align-items-center">
                    <div class="profile-img-holder me-2">
                        <div class="profile-img icon d-flex align-items-center justify-content-center {{ Auth::user()->image === null ? '' : 'd-none' }} profile-placeholder">
                            <i class="bi bi-person display-1"></i>
                        </div>
                        <img src="{{ asset(Str::replaceFirst('public', 'storage', Auth::user()->image)) }}" alt="{{ Auth::user()->name }} Image"
                            class="profile-img {{ Auth::user()->image === null ? 'd-none' : "" }} profile-image">
                        <input type="hidden" value="{{ csrf_token() }}" id="img-token">
                        <button class="profile-img-btn" id="upload-btn">
                            <i class="bi bi-camera"></i>
                        </button>
                        <input type="file" id="img-upload" hidden>
                    </div>
                    <div>
                        <h3>{{ Auth::user()->name }}</h3>
                        <p>{{ Auth::user()->role }}</p>
                    </div>
                </div>
            </div>
        </div>

        <ul>
            @foreach($errors->all() as $message)
                <li class="text-danger">{{ $message }}</li>
            @endforeach
        </ul>

        <ul class="nav nav-underline" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">{{ __("personal_information") }}</button>
            </li>
            <li class="nav-item">
              <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="true">{{ __("change_password") }}</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="pills-pin-tab" data-bs-toggle="pill" data-bs-target="#pills-pin" type="button" role="tab" aria-controls="pills-pin" aria-selected="true">
                    {{ __("withdrawal_pin") }}
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="pills-bank-tab" data-bs-toggle="pill" data-bs-target="#pills-bank" type="button" role="tab" aria-controls="pills-bank" aria-selected="true">
                    {{ __("bank_details") }}
                </button>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                <div class="py-3 py-xxl-4">
                    <form action="/{{ App::currentLocale() }}/distributor/profile/personal-information" method="POST"
                        id="personal-form">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-12 col-md-4 col-xxl-3 mb-3 mb-md-0">
                                <label for="name">{{ __("name") }}</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ Auth::user()->name }}">
                                <small class="text-danger d-none"></small>
                            </div>
                            <div class="col-12 col-md-4 col-xxl-3">
                                <label for="email">{{ __("email") }}</label>
                                <input type="email" id="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-md-4 col-xxl-3 mb-3 mb-md-0">
                                <label for="phone">{{ __("phone_number") }}</label>
                                <input type="text" readonly id="phone" class="form-control" value="{{ Auth::user()->distributor->phone_number }}">
                                <small class="text-danger d-none"></small>
                            </div>
                            <div class="col-12 col-md-4 col-xxl-3">
                                <label for="wave">{{ __("wave_number") }}</label>
                                <input type="number" id="wave" class="form-control" value="{{ Auth::user()->distributor->wave }}" readonly>
                            </div>
                        </div>
                        <button class="btn btn-success">
                            <i class="bi bi-save"></i> {{ __("save") }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                <div class="py-3 py-xxl-4">
                    <form action="/{{ App::currentLocale() }}/distributor/profile/password-change" method="POST" id="password-form">
                        @csrf
                        <div class="row flex-column">
                            <div class="col-12 col-md-4 col-xxl-3 mb-3">
                                <label for="current_password">{{ __("current_password") }}</label>
                                <input type="password" name="current_password" id="current_password" class="form-control">
                                <small class="text-danger d-none"></small>
                            </div>
                            <div class="col-12 col-md-4 col-xxl-3 mb-3">
                                <label for="new_password">{{ __("new_password") }}</label>
                                <input type="password" id="new_password" name="new_password" class="form-control">
                                <small class="text-danger d-none"></small>
                            </div>
                            <div class="col-12 col-md-4 col-xxl-3 mb-3">
                                <label for="confirm_password">{{ __("confirm_password") }}</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                                <small class="text-danger d-none"></small>
                            </div>
                        </div>
                        <button class="btn btn-success">
                            <i class="bi bi-save"></i> {{ __("save") }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="tab-pane fade" id="pills-pin" role="tabpanel" aria-labelledby="pills-pin-tab" tabindex="0">
                <div class="py-3 py-xxl-4">
                    @if (Auth::user()->distributor->code === null)
                        <form action="/{{ App::currentLocale() }}/distributor/profile/set-pin" method="POST" id="withdrawal-form">
                            @csrf
                            <div class="row flex-column">
                                <div class="col-12 col-md-4 col-xxl-3 mb-3">
                                    <label for="code">{{ __("pin") }}</label>
                                    <input type="password" name="code" id="code" class="form-control">
                                    <small class="text-danger d-none"></small>
                                </div>
                                <p>
                                    <i class="bi bi-info-circle"></i> {{ __("pin_info") }}
                                </p>
                            </div>
                            <button class="btn btn-success" type="submit">
                                <i class="bi bi-save"></i> {{ __("save") }}
                            </button>
                        </form>
                    @else
                        <form action="/{{ App::currentLocale() }}/distributor/profile/change-pin" method="POST" id="change-withdrawal-form">
                            @csrf
                            <div class="row flex-column">
                                <div class="col-12 col-md-4 col-xxl-3 mb-3">
                                    <label for="current_pin">{{ __("current_pin") }}</label>
                                    <input type="password" name="current_pin" id="current_pin" class="form-control">
                                    <small class="text-danger d-none"></small>
                                </div>
                                <div class="col-12 col-md-4 col-xxl-3 mb-3">
                                    <label for="new_pin">{{ __("new_pin") }}</label>
                                    <input type="password" name="new_pin" id="new_pin" class="form-control">
                                    <small class="text-danger d-none"></small>
                                </div>
                                <div class="col-12 col-md-4 col-xxl-3 mb-3">
                                    <label for="confirm_pin">{{ __("confirm_pin") }}</label>
                                    <input type="password" name="confirm_pin" id="confirm_pin" class="form-control">
                                    <small class="text-danger d-none"></small>
                                </div>
                            </div>
                            <button class="btn btn-success" type="submit">
                                <i class="bi bi-save"></i> {{ __("save") }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="tab-pane fade" id="pills-bank" role="tabpanel" aria-labelledby="pills-bank-tab" tabindex="0">
                <div class="py-3 py-xxl-4">
                    <form action="/{{ App::currentLocale() }}/distributor/profile/bank" method="POST" id="bank-details-form">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="form-group mb-3">
                                    <label for="full_name">Full name</label>
                                    <input type="text" name="full_name" id="full_name" class="form-control">
                                    <small class="text-danger d-none"></small>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="bank_name">Bank name</label>
                                    <input type="text" name="bank_name" id="bank_name" class="form-control">
                                    <small class="text-danger d-none"></small>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="bank_branch">Bank branch</label>
                                    <input type="text" name="bank_branch" id="bank_branch" class="form-control">
                                    <small class="text-danger d-none"></small>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="beneficiary_name">Beneficiary / Account name</label>
                                    <input type="text" name="beneficiary_name" id="beneficiary_name" class="form-control">
                                    <small class="text-danger d-none"></small>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="account_number">Account number</label>
                                    <input type="text" name="account_number" id="account_number" class="form-control" minlength="8" maxlength="17">
                                    <small class="text-danger d-none"></small>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-4 mb-3 mb-md-0">
                                <div class="form-group mb-3">
                                    <label for="rib">RIB number</label>
                                    <input type="text" name="rib" id="rib" class="form-control" minlength="2" maxlength="3">
                                    <small class="text-danger d-none"></small>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="iban">IBAN number</label>
                                    <input type="text" name="iban" id="iban" class="form-control" maxlength="23">
                                    <small class="text-danger d-none"></small>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="swift_number">Swift / BIC number</label>
                                    <input type="text" name="swift_number" id="swift_number" class="form-control" maxlength="8">
                                    <small class="text-danger d-none"></small>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="phone_number">Phone Number</label>
                                    <input type="text" name="phone_number" id="phone_number" class="form-control">
                                    <small class="text-danger d-none"></small>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-success" type="submit">
                            <i class="bi bi-save"></i> {{ __("save") }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push("scripts")
        <script src="{{ asset("assets/js/profile.js") }}"></script>
        <script src="{{ asset("assets/js/distributor/profile.js") }}"></script>
        <script src="{{ asset("assets/js/bank-details.js") }}"></script>
        <script src="{{ asset("assets/js/distributor/reset-maintenance-store.js") }}"></script>
    @endpush
</x-layout.distributor>
