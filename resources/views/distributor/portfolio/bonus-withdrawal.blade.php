<x-layout.distributor>
    <x-slot name="title">Bonus Withdrawal</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-2 mb-md-0">{{ __("bonus_withdrawal") }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/distributor">{{ __("home") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("bonus_withdrawal") }}</li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid p-0 mb-3">
        <div class="row">
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $firstHeading = __("total_requests"); @endphp
                <x-model-summary :title="$firstHeading" icon="list" :number="$totalRequest" class="bg-main" />
            </div>
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $secondHeading = __("pending"); @endphp
                <x-model-summary :title="$secondHeading" icon="clock-history" :number="$pending" class="bg-tertiary" />
            </div>
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $thirdHeading = __("approved"); @endphp
                <x-model-summary :title="$thirdHeading" icon="check2" :number="$approved" class="bg-other" />
            </div>
        </div>
    </div>

    <div class="card border shadow-sm">
        <div class="card-header bg-white p-3 d-block d-md-flex align-items-center justify-content-between">
            <h5 class="m-0">{{ __("available") }}</h5>
            @if (session()->get("isWithdrawalDay") === true)
                <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="bi bi-cash"></i> {{ __("withdraw") }}
                </button>
            @endif
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover display" id="product-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __("date_time") }}</th>
                            <th>{{ __("amount") }}</th>
                            <th>{{ __("deduction") }} (5%)</th>
                            <th>{{ __("amount_paid") }}</th>
                            <th>{{ __("status") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($withdrawals as $detail)
                            <tr>
                                <td>{{ $detail->id }}</td>
                                <td>{{ $detail->created_at }}</td>
                                <td>${{ number_format($detail->amount, 2) }}</td>
                                <td>${{ number_format($detail->deduction, 2) }}</td>
                                @php $value = $detail->amount - $detail->deduction; @endphp
                                <td>${{ number_format($value, 2) }}</td>
                                <td>
                                    @if ($detail->status === "PENDING")
                                        <span class="badge bg-danger">{{ $detail->status }}</span>
                                    @else
                                        <span class="badge bg-success">{{ $detail->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __("request_withdrawal") }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/{{ App::currentLocale() }}/distributor/bonus-withdrawal/request" method="post" id="form">
                    @csrf
                    @method("POST")

                    <div class="modal-body">
                        <input type="hidden" value="{{ Auth::user()->distributor->portfolio->commission_wallet }}" id="wallet">
                        <div class="form-group mb-2">
                            <label for="amount">{{ __("amount") }}</label>
                            <input type="text" name="amount" id="amount" class="form-control">
                            <small class="text-danger d-none"></small>
                        </div>
                        <p>
                            <i class="bi bi-info-circle"></i> {{ __("withdrawal_request_info") }}
                        </p>
                        <div class="form-group mt-3 mb-2">
                            <label for="code">{{ __("withdrawal_code") }}</label>
                            <input type="password" name="code" id="code" class="form-control">
                            <small class="text-danger d-none"></small>
                        </div>
                        <p>
                            <i class="bi bi-info-circle"></i> Don't have a withdrawal code? <a href="/{{ App::currentLocale() }}/distributor/profile">Click here</a> to create one now
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("close") }}</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check2"></i> {{ __("request") }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push("scripts")
        <script src="{{ asset("assets/js/distributor/bonus-withdrawal.js") }}"></script>

        <script>
            $(document).ready(function() {
                $("#product-table").DataTable();
            });
        </script>
        <script src="{{ asset("assets/js/distributor/reset-maintenance-store.js") }}"></script>
    @endpush
</x-layout.distributor>
