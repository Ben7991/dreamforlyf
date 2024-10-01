<x-layout.admin>
    <x-slot name="title">Stockist Withdrawals</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-2 mb-md-0">{{ __("details") }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin/stockist-withdrawals">{{ __("stockist_withdrawal") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("details") }}</li>
            </ol>
        </nav>
    </div>

    @php $goBackHeading = __("stockist_withdrawal") @endphp
    <x-go-back path="/{{ App::currentLocale() }}/admin/stockist-withdrawals" :title="$goBackHeading" />

    <div class="card mb-3 mb-xxl-4">
        <div class="card-header p-3 bg-white">
            <h5 class="m-0">{{ __("details") }}</h5>
        </div>
        <div class="card-body">
            <form action="/{{ App::currentLocale() }}/admin/stockist-withdrawals/{{ $withdrawalDetails->id }}/approve" method="POST">
                @csrf
                @method("PUT")
                <div class="row">
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="date_time">{{ __("date_time") }}</label>
                        <input type="datetime" id="date_time" class="form-control" value="{{ $withdrawalDetails->created_at }}" readonly>
                    </div>
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="amount">{{ __("amount") }}</label>
                        <input type="text" id="amount" class="form-control" value="$ {{ $withdrawalDetails->amount }}" readonly>
                    </div>
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="amount">{{ __("deduction") }} (5%)</label>
                        <input type="text" id="amount" class="form-control" value="$ {{ $withdrawalDetails->amount * 0.05 }}" readonly>
                    </div>
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="amount_paid">{{ __("amount_paid") }}</label>
                        <input type="text" id="amount_paid" class="form-control" value="$ {{ $withdrawalDetails->amount - ($withdrawalDetails->amount * 0.05) }}" readonly>
                    </div>
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="distributor">{{ __("stockists") }}</label>
                        <input type="text" id="distributor" class="form-control" value="{{ $withdrawalDetails->code }}" readonly>
                    </div>
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="mode">Withdrawal Mode</label>
                        <input type="text" id="mode" class="form-control" value="{{ $withdrawalDetails->mode }}" readonly>
                    </div>
                    @if ($withdrawalDetails->mode === "MOMO")
                        <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                            <label for="mode">{{ __("wave_number") }}</label>
                            <input type="text" id="mode" class="form-control" value="{{ $withdrawalDetails->distributor->wave }}" readonly>
                        </div>
                    @endif
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="status">{{ __("status") }}</label>
                        <input type="text" id="status" class="form-control" value="{{ $withdrawalDetails->status }}" readonly>
                    </div>
                </div>
                @if ($withdrawalDetails->status !== "APPROVED")
                    <button class="btn btn-success" type="submit">
                        <i class="bi bi-check2"></i> {{ __('approve_withdrawal') }}
                    </button>
                @endif
            </form>
        </div>
    </div>

    @if ($withdrawalDetails->mode === "BANK")
        <div class="card mb-3 mb-xxl-4">
            <div class="card-header p-3 bg-white">
                <h5 class="m-0">Bank Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="full_name">Full name</label>
                        <input type="text" id="full_name" class="form-control" value="{{ $bankDetails->full_name }}" readonly>
                    </div>
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="bank_name">Bank Name</label>
                        <input type="text" id="bank_name" class="form-control" value="{{ $bankDetails->bank_name }}" readonly>
                    </div>
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="bank_branch">Bank Branch</label>
                        <input type="text" id="bank_branch" class="form-control" value="{{ $bankDetails->bank_branch }}" readonly>
                    </div>
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="beneficiary_name">Beneficiary Name</label>
                        <input type="text" id="beneficiary_name" class="form-control" value="{{ $bankDetails->beneficiary_name }}" readonly>
                    </div>
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="account_number">Account Number</label>
                        <input type="text" id="account_number" class="form-control" value="{{ $bankDetails->account_number }}" readonly>
                    </div>
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="iban_number">Iban Number</label>
                        <input type="text" id="iban_number" class="form-control" value="{{ $bankDetails->iban_number }}" readonly>
                    </div>
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="swift_number">Swift Number</label>
                        <input type="text" id="swift_number" class="form-control" value="{{ $bankDetails->swift_number }}" readonly>
                    </div>
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="phone_number">Phone Number</label>
                        <input type="text" id="phone_number" class="form-control" value="{{ $bankDetails->phone_number }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    @endif

</x-layout.admin>
