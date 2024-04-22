<x-layout.admin>
    <x-slot name="title">Dashboard</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-2 mb-md-0">{{ __("dashboard") }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item active" aria-current="page">{{ __("home") }}</li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid p-0">
        <div class="row mb-4">
            <div class="col-12 col-xxl-9 mb-4 mb-xxl-0">
                <div class="row mb-3 mb-xxl-4">
                    <div class="col-12 col-md-4 col-xl-4 mb-3 mb-xl-0">
                        @php $firstSummaryHeading = __("pending_orders"); @endphp
                        <x-model-summary :title="$firstSummaryHeading" icon="clock-history" :number="$pendingOrderCount" class="bg-main" />
                    </div>
                    <div class="col-12 col-md-4 col-xl-4 mb-3 mb-xl-0">
                        @php $secondSummaryHeading = __("qualified_ranks"); @endphp
                        <x-model-summary :title="$secondSummaryHeading" icon="check2-circle" :number="$qualifiedRankCount" class="bg-tertiary" />
                    </div>
                    <div class="col-12 col-md-4 col-xl-4 mb-3 mb-xl-0">
                        @php $thirdSummaryHeading = __("qualified_pool"); @endphp
                        <x-model-summary :title="$thirdSummaryHeading" icon="database-check" :number="$qualifiedPoolCount" class="bg-other" />
                    </div>
                </div>

                <div class="card mb-3 mb-xxl-4">
                    <div class="card-header p-3 bg-white d-flex align-items-center justify-content-between">
                        <h5 class="m-0">{{ __("bonus_withdrawal") }}</h5>
                        <a href="/{{ App::currentLocale() }}/admin/bonus-withdrawals" class="btn btn-link">
                            {{ __("see_more") }} <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover display" id="bonus-withdrawal-table">
                                <thead>
                                    <tr>
                                        <th>{{ __("date_time") }}</th>
                                        <th>{{ __("withdrawal_amount") }}</th>
                                        <th>{{ __("distributor") }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bonusWithdrawals as $bonusWithdrawal)
                                        <tr>
                                            <td>{{ $bonusWithdrawal->created_at }}</td>
                                            <td>{{ $bonusWithdrawal->amount }}</td>
                                            <td>{{ $bonusWithdrawal->distributor->user->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mb-3 mb-xxl-4">
                    <div class="card-header p-3 bg-white d-flex align-items-center justify-content-between">
                        <h5 class="m-0">{{ __("upgrades") }}</h5>
                        <a href="/{{ App::currentLocale() }}/admin/upgrade-history" class="btn btn-link">
                            {{ __("see_more") }} <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover display" id="upgrades-table">
                                <thead>
                                    <tr>
                                        <th>{{ __("date_time") }}</th>
                                        <th>{{ __("distributor") }}</th>
                                        <th>{{ __("previous_package") }}</th>
                                        <th>{{ __("current_package") }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upgrades as $upgrade)
                                        <tr>
                                            <td>{{ $upgrade->created_at }}</td>
                                            <td>{{ $upgrade->distributor->user->name }}</td>
                                            <td>{{ $upgrade->upgradeType->current_package->name }}</td>
                                            <td>{{ $upgrade->registrationPackage->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xxl-3 mb-4 mb-xxl-0">
                <h5 class="mb-3 mb-xxl-4">{{ __("overall_history") }}</h5>
                <div class="row">
                    <div class="col-12 col-md-4">
                        @php $overAllHeadingOne = __("low_quantity_products"); @endphp
                        @php $productCount = session()->get("product_count"); @endphp
                        <x-dashboard-summary :title="$overAllHeadingOne" :value="$productCount" icon="capsule"/>
                    </div>
                    <div class="col-12 col-md-4">
                        @php $overAllHeadingTwo = __("award") @endphp
                        <x-dashboard-summary :title="$overAllHeadingTwo" :value="$awardCount" icon="award"/>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push("scripts")
        <script src="{{ asset("assets/js/admin/index.js") }}"></script>
    @endpush
</x-layout.admin>
