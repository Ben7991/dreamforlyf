<x-layout.distributor>
    <x-slot name="title">Dashboard</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-2 mb-md-0">
            {{ __("welcome_back") }}, {{ Auth::user()->name }}
        </h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item active">{{ __("home") }}</li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid p-0">
        <div class="row mb-4">
            <div class="col-12 col-xxl-9 mb-4 mb-xxl-0">
                <div class="row mb-3 mb-xxl-4">
                    <div class="col-12 col-md-4 col-xl-4 mb-3 mb-xl-0">
                        @php $package = $currentPackage->name; @endphp
                        @php $firstHeading = __("current_package"); @endphp
                        <x-model-summary :title="$firstHeading" icon="award" :number="$package" class="bg-main" />
                    </div>
                    <div class="col-12 col-md-4 col-xl-4 mb-3 mb-xl-0">
                        @php $secondHeading = __("current_rank"); @endphp
                        <x-model-summary :title="$secondHeading" icon="ladder" number="None" class="bg-tertiary" />
                    </div>
                    <div class="col-12 col-md-4 col-xl-4">
                        @php $thirdHeading = __("remaining_days"); @endphp
                        <input type="hidden" id="remainingDays" value="{{ $remainingDays }}">
                        <x-model-summary :title="$thirdHeading" icon="gear" :number="$remainingDays" class="bg-other" />
                    </div>
                </div>

                <div class="row mb-3 mb-xxl-4">
                    <div class="col-12 col-md-4 col-xxl-4 mb-3 mb-lg-0">
                        <div class="border rounded p-3 bg-white">
                            <p class="mb-2">{{ __("distributor_id") }}</p>
                            <h5 class="m-0">{{ Auth::id() }}</h5>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-xxl-4">
                        <div class="border rounded p-3 bg-white">
                            <label for="link">{{ __("left_referral_link") }}</label>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="text" class="form-control" id="link" readonly
                                    value="http://localhost:8000/{{ App::currentLocale() }}/sponsor?id={{ Auth::user()->id }}&token={{ $token }}&side=left">
                                <button class="btn btn-secondary position-relative btn-referral-link">
                                    <span class="link-response">
                                        <span class="link-response-holder">{{ __("copied") }}</span>
                                    </span>
                                    <i class="bi bi-clipboard-check"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-xxl-4">
                        <div class="border rounded p-3 bg-white">
                            <label for="link">{{ __("right_referral_link") }}</label>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="text" class="form-control" id="link" readonly
                                    value="http://localhost:8000/{{ App::currentLocale() }}/sponsor?id={{ Auth::user()->id }}&token={{ $token }}&side=right">
                                <button class="btn btn-secondary position-relative btn-referral-link">
                                    <span class="link-response">
                                        <span class="link-response-holder">{{ __("copied") }}</span>
                                    </span>
                                    <i class="bi bi-clipboard-check"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border shadow-sm">
                    <div class="card-header bg-white p-3">
                        <h5 class="m-0">{{ __("referred_members") }}</h5>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table table-hover display" id="table">
                                <thead>
                                    <tr>
                                        <th>{{ __("name") }}</th>
                                        <th>{{ __("current_package") }}</th>
                                        <th>{{ __("rank") }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($referredDistibutors as $referredDistributor)
                                        <tr>
                                            <td>{{ $referredDistributor["name"] }}</td>
                                            <td>{{ $referredDistributor["currentPackage"] }}</td>
                                            <td>{{ $referredDistributor["rank"] }}</td>
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
                @php
                    $distributor = Auth::user()->distributor;
                    $currentBalance = number_format($distributor->portfolio->current_balance, 2);
                    $commission = number_format($distributor->portfolio->commission_wallet, 2);
                @endphp

                <div class="row">
                    <div class="col-12 col-md-6 col-xl-4 col-xxl-12 mb-3">
                        @php $firstHistoryHeading = __("total_orders"); @endphp
                        <x-model-summary :title="$firstHistoryHeading" icon="clock-history" :number="$totalOrders" class="bg-green" />
                    </div>
                    <div class="col-12 col-md-6 col-xl-4 col-xxl-12 mb-3">
                        @php $formattedCommission = '$' . $commission @endphp
                        @php $secondHistoryHeading = __("total_commission"); @endphp
                        <x-model-summary :title="$secondHistoryHeading" icon="cash" :number="$formattedCommission" class="bg-orange" />
                    </div>
                    <div class="col-12 col-md-6 col-xl-4 col-xxl-12">
                        @php $formattedCurrentBalance = '$' . $currentBalance @endphp
                        @php $thirdHistoryHeading = __("current_balance"); @endphp
                        <x-model-summary :title="$thirdHistoryHeading" icon="wallet" :number="$formattedCurrentBalance" class="bg-secondary" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __("account_maintenance") }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>
                        @if ($remainingDays > 0)
                            {{ __("maintenance_message_1", ["dayCount" => $remainingDays]) }}
                        @elseif (($remainingDays === 0))
                            {{ __("maintenance_message_2") }}
                        @else
                            {{ __("maintenance_message_3", ["dayCount" => abs($remainingDays) ]) }}
                        @endif
                        {{ __("maintenance_message_4") }}: <strong class="fw-semibold text-danger">{{ __("pool_bonus") }}</strong>, <strong class="fw-semibold text-danger">{{ __("rank_award") }}</strong>, <strong class="fw-semibold text-danger">{{ __("upgrade_bonus") }}</strong>, <strong class="fw-semibold text-danger">{{ __("leadership_bonus") }}</strong>,
                        <strong class="fw-semibold text-danger">{{ __("binary_bonus") }}</strong>.
                    </p>
                    <hr>
                    <p>{{ __("maintenance_message_5") }}</p>
                    <ul>
                        <li class="fw-semibold">{{ __("referral_commission") }}</li>
                        <li class="fw-semibold">{{ __("personal_purchase") }}</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("close") }}</button>
                </div>
            </div>
        </div>
      </div>

    @push("scripts")
        <script src="{{ asset("assets/js/distributor/index.js") }}"></script>
        <script src="{{ asset("assets/js/distributor/reset-maintenance-store.js") }}"></script>
    @endpush
</x-layout.distributor>
