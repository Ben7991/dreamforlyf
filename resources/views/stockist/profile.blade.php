<x-layout.stockist>
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
              <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Personal Information</button>
            </li>
            <li class="nav-item">
              <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="true">Change Password</button>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                <div class="py-3 py-xxl-4">
                    <form action="/{{ App::currentLocale() }}/stockist/profile/personal-information" method="POST"
                        id="personal-form">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-12 col-md-4 col-xxl-3 mb-3 mb-md-0">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ Auth::user()->name }}">
                                <small class="text-danger d-none"></small>
                            </div>
                            <div class="col-12 col-md-4 col-xxl-3">
                                <label for="email">Email</label>
                                <input type="email" id="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
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
                    <form action="/{{ App::currentLocale() }}/stockist/profile/password-change" method="POST" id="password-form">
                        @csrf
                        <div class="row flex-column">
                            <div class="col-12 col-md-4 col-xxl-3 mb-3">
                                <label for="current_password">Current Password</label>
                                <input type="password" name="current_password" id="current_password" class="form-control">
                                <small class="text-danger d-none"></small>
                            </div>
                            <div class="col-12 col-md-4 col-xxl-3 mb-3">
                                <label for="new_password">New Password</label>
                                <input type="password" id="new_password" name="new_password" class="form-control">
                                <small class="text-danger d-none"></small>
                            </div>
                            <div class="col-12 col-md-4 col-xxl-3 mb-3">
                                <label for="confirm_password">Confirm Password</label>
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
        </div>
    </div>

    @push("scripts")
        <script src="{{ asset("assets/js/profile.js") }}"></script>
    @endpush
</x-layout.stockist>
