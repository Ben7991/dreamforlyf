<x-layout.home>
    <x-slot name="title">Opportunity</x-slot>

    <section class="bg-main dfl">
        <div class="container">
            <h1 class="text-white mb-3 mb-xl-4">{{ __("opportunity") }}</h1>
            <p class="description text-light">{{ __("opportunity_info") }}</p>
        </div>
    </section>

    <section class="dfl">
        <div class="container">
            <h3 class="mb-3 text-center">{{ __("getting_started") }}</h3>
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
    </section>

    <section class="dfl bg-light-subtle text-light-emphasis">
        <div class="container">
            <h3 class="mb-3 text-center">{{ __("bonus_explanations") }}</h3>
            <p class="mb-3 text-center description text-secondary">{{ __("bonus_explanations_desc") }}</p>
            <x-underline />

            <div class="row">
                <div class="col-12 col-md-4 mb-3 mb-xxl-5">
                    <h3 class="mb-3">{{ __("cash_back") }}</h3>
                    <p class="m-0 text-secondary description">{{ __("cash_back_desc") }}</p>
                </div>
                <div class="col-12 col-md-4 mb-3 mb-xxl-5">
                    <h3 class="mb-3">{{ __("referral_commission") }}</h3>
                    <p class="m-0 text-secondary description">{{ __("referral_commission_desc") }}</p>
                </div>
                <div class="col-12 col-md-4 mb-3 mb-xxl-5">
                    <h3 class="mb-3">{{ __("upgrade_bonus") }}</h3>
                    <p class="m-0 text-secondary description">{{ __("upgrade_bonus_desc") }}</p>
                </div>
                <div class="col-12 col-md-4 mb-3 mb-xxl-5">
                    <h3 class="mb-3">{{ __("binary_bonus") }}</h3>
                    <p class="m-0 text-secondary description">{{ __("binary_bonus_desc") }}</p>
                </div>
                <div class="col-12 col-md-4 mb-3 mb-xxl-5">
                    <h3 class="mb-3">{{ __("personal_purchase") }}</h3>
                    <p class="m-0 text-secondary description">{{ __("personal_purchase_desc") }}</p>
                </div>
                <div class="col-12 col-md-4 mb-3 mb-xxl-5">
                    <h3 class="mb-3">{{ __("maintenance_bonus") }}</h3>
                    <p class="m-0 text-secondary description">{{ __("maintenance_bonus_desc") }}</p>
                </div>
                <div class="col-12 col-md-4 mb-3 mb-xxl-5">
                    <h3 class="mb-3">{{ __("leadership_bonus") }}</h3>
                    <p class="m-0 text-secondary description">{{ __("leadership_bonus_desc") }}</p>
                </div>
                <div class="col-12 col-md-4 mb-3 mb-xxl-5">
                    <h3 class="mb-3">{{ __("award_bonus") }}</h3>
                    <p class="m-0 text-secondary description">{{ __("award_bonus_desc") }}</p>
                </div>
                <div class="col-12 col-md-4 mb-3 mb-xxl-5">
                    <h3 class="mb-3">{{ __("pool_bonus") }}</h3>
                    <p class="m-0 text-secondary description">{{ __("pool_bonus_desc") }}</p>
                </div>
            </div>
        </div>
    </section>

</x-layout.home>
