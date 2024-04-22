<x-layout.distributor>
    <x-slot name="title">Complan</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-2 mb-md-0">Complan</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">Complan</li>
            </ol>
          </nav>
    </div>

    <div class="container-fluid py-3">
        <h5 class="mb-3 text-center">{{ __("getting_started") }}</h5>
        <p class="mb-3 text-center description text-secondary">{{ __("getting_started_desc") }}</p>
        <x-underline />

        <div class="row justify-content-center">
            @foreach($packages as $package)
                <div class="col-6 col-md-4 col-xl-3 col-xxl-2 mb-3 mb-xxl-0">
                    <div class="text-center">
                        <span class="rounded bg-main text-white py-2 px-3 d-inline-block shadow">
                            <i class="bi bi-award fs-3"></i>
                        </span>
                        <p class="mt-3 mb-0 lead">{{ $package->name }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="container-fluid py-3">
        <h5 class="mb-3 text-center">{{ __("bonus_explanations") }}</h5>
        <p class="mb-3 text-center description text-secondary">{{ __("bonus_explanations_desc") }}</p>
        <x-underline />

        <div class="row">
            <div class="col-12 col-md-4 mb-3 mb-xxl-5">
                <h6 class="mb-3">{{ __("cash_back") }}</h6>
                <p class="m-0 text-secondary description">{{ __("cash_back_desc") }}</p>
            </div>
            <div class="col-12 col-md-4 mb-3 mb-xxl-5">
                <h6 class="mb-3">{{ __("referral_commission") }}</h6>
                <p class="m-0 text-secondary description">{{ __("referral_commission_desc") }}</p>
            </div>
            <div class="col-12 col-md-4 mb-3 mb-xxl-5">
                <h6 class="mb-3">{{ __("upgrade_bonus") }}</h6>
                <p class="m-0 text-secondary description">{{ __("upgrade_bonus_desc") }}</p>
            </div>
            <div class="col-12 col-md-4 mb-3 mb-xxl-5">
                <h6 class="mb-3">{{ __("binary_bonus") }}</h6>
                <p class="m-0 text-secondary description">{{ __("binary_bonus_desc") }}</p>
            </div>
            <div class="col-12 col-md-4 mb-3 mb-xxl-5">
                <h6 class="mb-3">{{ __("personal_purchase") }}</h6>
                <p class="m-0 text-secondary description">{{ __("personal_purchase_desc") }}</p>
            </div>
            <div class="col-12 col-md-4 mb-3 mb-xxl-5">
                <h6 class="mb-3">{{ __("maintenance_bonus") }}</h6>
                <p class="m-0 text-secondary description">{{ __("maintenance_bonus_desc") }}</p>
            </div>
            <div class="col-12 col-md-4 mb-3 mb-xxl-5">
                <h6 class="mb-3">{{ __("leadership_bonus") }}</h6>
                <p class="m-0 text-secondary description">{{ __("leadership_bonus_desc") }}</p>
            </div>
            <div class="col-12 col-md-4 mb-3 mb-xxl-5">
                <h6 class="mb-3">{{ __("award_bonus") }}</h6>
                <p class="m-0 text-secondary description">{{ __("award_bonus_desc") }}</p>
            </div>
            <div class="col-12 col-md-4 mb-3 mb-xxl-5">
                <h6 class="mb-3">{{ __("pool_bonus") }}</h6>
                <p class="m-0 text-secondary description">{{ __("pool_bonus_desc") }}</p>
            </div>
        </div>
    </div>

    @push("scripts")
        <script src="{{ asset("assets/js/distributor/reset-maintenance-store.js") }}"></script>
    @endpush
</x-layout.distributor>
