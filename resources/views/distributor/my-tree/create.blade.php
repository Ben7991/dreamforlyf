<x-layout.distributor>
    <x-slot name="title">My Tree</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3 mb-xxl-4">
        <h4 class="mb-2 mb-md-0">
            {{ __("add_distributor") }}
        </h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("add_distributor") }}</li>
            </ol>
        </nav>
    </div>

    @php $goBackHeading = __("my_tree"); @endphp
    <x-go-back path="/{{ App::currentLocale() }}/distributor/my-tree" :title="$goBackHeading"/>

    <div class="container-fluid p-3 p-xxl-4 bg-white rounded border shadow-sm mb-4">
        <h5 class="mb-3">{{ __("provide_distributor_details") }}</h5>
        <ul>
            <li>If you don't have a wave number, we advice you speak to your upline so that you can use his wave number in-case he/she has. If not we advice you get one since all bonus to be paid will require a wave number</li>
        </ul>

        <ul>
            @foreach($errors->all() as $message)
                <li class="text-danger">{{ $message }}</li>
            @endforeach
        </ul>

        <form action="/{{ App::currentLocale() }}/distributor/my-tree/register" method="POST" id="form">
            @csrf

            <div class="row mb-3">
                <div class="col-12 col-md-6 col-xl-4 col-xxl-3 mb-3">
                    <label for="name">{{ __("name") }}</label>
                    <input type="text" name="name" id="name" class="form-control" >
                    <small class="text-danger d-none"></small>
                </div>
                <div class="col-12 col-md-6 col-xl-4 col-xxl-3 mb-3">
                    <label for="email">{{ __("email") }}</label>
                    <input type="email" name="email" id="email" class="form-control">
                    <small class="text-danger d-none"></small>
                </div>
                <div class="col-12 col-md-6 col-xl-4 col-xxl-3 mb-3">
                    <label for="country">{{ __("country") }}</label>
                    <select name="country" id="country" class="form-select">
                        <option value="">Select country</option>
                    </select>
                    <small class="text-danger d-none"></small>
                </div>
                <div class="col-12 col-md-6 col-xl-4 col-xxl-3 mb-3">
                    <label for="city">{{ __("city") }}</label>
                    <input type="text" name="city" id="city" class="form-control">
                    <small class="text-danger d-none"></small>
                </div>
                <div class="col-12 col-md-6 col-xl-4 col-xxl-3 mb-3">
                    <label for="package_id">{{ __("membership_package") }}</label>
                    <select name="package_id" id="package_id" class="form-select">
                        <option value="">{{ __("select_membership_package") }}</option>
                        @foreach ($packages as $package)
                            <option value="{{ $package->id }}">{{ $package->name }}</option>
                        @endforeach
                    </select>
                    <small class="text-danger d-none"></small>
                </div>
                <div class="col-12 col-md-6 col-xl-4 col-xxl-3 mb-3">
                    <label for="phone_number">{{ __("phone_number") }}</label>
                    <input type="tel" name="phone_number" id="phone_number" class="form-control">
                    <small class="text-danger d-none"></small>
                </div>
                <div class="col-12 col-md-6 col-xl-4 col-xxl-3 mb-3">
                    <label for="wave">{{ __("wave_number") }}</label>
                    <input type="tel" name="wave" id="wave" class="form-control">
                    <small class="text-danger d-none"></small>
                </div>
                <div class="col-12 col-md-6 col-xl-4 col-xxl-3 mb-3">
                    <label for="leg">{{ __("leg") }}</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="leg" id="left_leg" value="1st">
                            <label class="form-check-label" for="left_leg">{{ __("left_leg") }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="leg" id="right_leg" value="2nd">
                            <label class="form-check-label" for="right_leg">{{ __("right_leg") }}</label>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-4 col-xxl-3 mb-3">
                    <label for="stockist_id">{{ __("select_stockist") }}</label>
                    <select name="stockist_id" id="stockist_id" class="form-select">
                        <option value="">{{ __("select_stockist") }}</option>
                        @foreach($stockists as $stockist)
                            <option value="{{ $stockist->id }}">{{ $stockist->code }}</option>
                        @endforeach
                    </select>
                    <small class="text-danger d-none"></small>
                </div>
            </div>

            <div class="mb-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="m-0">{{ __("select_package_type") }}</h5>
                    <div class="spinner-border text-primary d-none" role="status" id="package-spinner">
                        <span class="visually-hidden">{{ __("loading") }}</span>
                    </div>
                </div>
                <div class="row" id="package-type-holder">
                    {{-- <div class="col-12 col-md-4 col-xl-3 col-xxl-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                            <div class="d-flex flex-column">
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Type A
                                </label>
                                <img src="{{ asset("assets/img/starter-b.png") }}" alt="" class="img-fluid img-thumbnail">
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>

            <button type="submit" class="btn btn-success btn-submit">
                <span class="main-btn">
                    <i class="bi bi-save"></i> {{ __("add_distributor") }}
                </span>
                <x-submit-spinner />
            </button>
            <p class="my-3">
                <i class="bi bi-info-circle"></i> {{ __("registration_btn_action") }}
            </p>
        </form>
    </div>

    <div class="img-modal">
        <div class="img-modal-container">
            <button class="btn img-modal-btn">
                <i class="bi bi-x fs-3"></i>
            </button>
            <img src="{{ asset("assets/img/starter-b.png") }}" class="img-modal-image">
        </div>
    </div>


    @push("scripts")
        <script src="{{ asset("assets/js/distributor/my-tree/create.js") }}"></script>
        <script src="{{ asset("assets/js/distributor/reset-maintenance-store.js") }}"></script>
    @endpush
</x-layout.distributor>
