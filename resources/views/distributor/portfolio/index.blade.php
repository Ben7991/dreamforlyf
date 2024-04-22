<x-layout.distributor>
    <x-slot name="title">Portfolios</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-2 mb-md-0">
            {{ __("portfolio") }}
        </h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("portfolio") }}</li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid p-0 mb-3 mb-xxl-4 z-n1">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white p-3">
                <h5 class="m-0">{{ __("what_are_these") }}</h5>
            </div>
            <div class="card-body row">
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="p-2 rounded">
                        <h6>{{ __("current_balance") }}</h6>
                        <p class="text-secondary">{{ __("current_balance_description") }}</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="p-2 rounded">
                        <h6>{{ __("commission_wallet") }}</h6>
                        <p class="text-secondary">{{ __("commission_wallet_description") }}</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="p-2 rounded">
                        <h6>{{ __("leadership_wallet") }}</h6>
                        <p class="text-secondary">{{ __("leadership_wallet_description") }}</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="p-2 rounded">
                        <h6>{{ __("personal_wallet") }}</h6>
                        <p class="text-secondary">{{ __("personal_wallet_description") }}</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-6">
                    <div class="p-2 rounded">
                        <h6>{{ __("total_withdrawal_wallet") }}</h6>
                        <p class="text-secondary">{{ __("withdrawal_description") }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid p-0 mb-4">
        <div class="row">
            <div class="col-12 col-md-4 col-xl-4 col-xxl-3 mb-3 mb-xxl-4">
                <div class="rounded bg-main p-3 p-xxl-4 shadow-sm d-flex justify-content-between">
                    <i class="bi bi-wallet text-white fs-3"></i>
                    <div class="text-end">
                        <p class="mt-0 mb-2 text-light">{{ __("current_balance") }}</p>
                        <h4 class="text-white">${{ number_format($portfolio->current_balance, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 col-xl-4 col-xxl-3 mb-3 mb-xxl-4">
                <div class="rounded bg-tertiary p-3 p-xxl-4 shadow-sm d-flex justify-content-between">
                    <i class="bi bi-cash-stack text-white fs-3"></i>
                    <div class="text-end">
                        <p class="mt-0 mb-2 text-light">{{ __("commission_wallet") }}</p>
                        <h4 class="text-white">${{ number_format($portfolio->commission_wallet, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 col-xl-4 col-xxl-3 mb-3 mb-xxl-4">
                <div class="rounded bg-secondary p-3 p-xxl-4 shadow-sm d-flex justify-content-between">
                    <i class="bi bi-graph-up-arrow text-white fs-3"></i>
                    <div class="text-end">
                        <p class="mt-0 mb-2 text-light">{{ __("leadership_weekly_point") }}</p>
                        <h4 class="text-white">{{ number_format($leadershipWeelyPoint) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 col-xl-4 col-xxl-3 mb-3 mb-xxl-4">
                <div class="rounded bg-other p-3 p-xxl-4 shadow-sm d-flex justify-content-between">
                    <i class="bi bi-wallet2 text-white fs-3"></i>
                    <div class="text-end">
                        <p class="mt-0 mb-2 text-light">{{ __("leadership_wallet") }}</p>
                        <h4 class="text-white">${{ number_format($leadershipWallet, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 col-xl-4 col-xxl-3 mb-3 mb-xxl-4">
                <div class="rounded bg-orange p-3 p-xxl-4 shadow-sm d-flex justify-content-between">
                    <i class="bi bi-cash-coin text-white fs-3"></i>
                    <div class="text-end">
                        <p class="mt-0 mb-2 text-light">{{ __("personal_wallet") }}</p>
                        <h4 class="text-white">${{ number_format($personalWallet, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 col-xl-4 col-xxl-3 mb-3 mb-xxl-4">
                <div class="rounded bg-green p-3 p-xxl-4 shadow-sm d-flex justify-content-between">
                    <i class="bi bi-box-arrow-up text-white fs-3"></i>
                    <div class="text-end">
                        <p class="mt-0 mb-2 text-light">{{ __("total_withdrawal_wallet") }}</p>
                        <h4 class="text-white">${{ number_format($totalWithdrawals, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push("scripts")
        <script src="{{ asset("assets/js/distributor/reset-maintenance-store.js") }}"></script>
    @endpush
</x-layout.distributor>
