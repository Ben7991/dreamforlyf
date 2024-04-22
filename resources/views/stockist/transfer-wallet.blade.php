<x-layout.stockist>
    <x-slot name="title">Wallet</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-2 mb-md-0">{{ __("transfer_wallet") }}</h4>

        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/stockist">{{ __("home") }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __("transfer_wallet") }}</li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid p-0 mb-3">
        <div class="row">
            <div class="col-12 col-md-4 col-xxl-3 mb-2 mb-md-0">
                @php $secondHeading = __("personal_wallet"); $amount = "$ " . number_format(Auth::user()->stockist->wallet, 2); @endphp
                <x-model-summary :title="$secondHeading" icon="clock-history" :number="$amount" class="bg-main" />
            </div>
        </div>
    </div>

    <div class="card border shadow-sm">
        <div class="card-header bg-white p-3 d-block d-md-flex align-items-center justify-content-between">
            <h5 class="m-0">{{ __("available") }}</h5>
            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="bi bi-send"></i> {{ __("transfer_wallet") }}
            </button>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover display" id="product-table">
                    <thead>
                        <tr>
                            <th>{{ __("date_time") }}</th>
                            <th>{{ __("distributor_id") }}</th>
                            <th>{{ __("distributor") }}</th>
                            <th>{{ __("amount") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transfers as $transfer)
                            <tr>
                                <td>{{ $transfer->date_added }}</td>
                                <td>{{ $transfer->id }}</td>
                                <td>{{ $transfer->name }}</td>
                                <td>${{ number_format($transfer->amount, 2) }}</td>
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __("transfer_wallet") }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <input type="hidden" id="locale" value="{{ App::currentLocale() }}">
                <form method="POST" id="form">
                    @csrf
                    @method("PUT")

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="search">{{ __("search") }}</label>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="search" id="search" class="form-control" placeholder="{{ __("search_by_id") }}">
                                <button type="button" class="btn btn-secondary" id="search-btn">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                            <small class="text-danger d-none" id="search-error">Distributor doesn't exist</small>
                        </div>
                        <hr>

                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="m-0">{{ __("distributor") }}</h6>
                            <span class="loader d-none">
                                <div class="d-flex align-items-center gap-1">
                                    <div class="spinner-border spinner-border-sm" role="status" id="loader">
                                        <span class="visually-hidden">{{ __("loading") }}</span>
                                    </div>
                                    {{ __("loading") }}
                                </div>
                            </span>
                        </div>
                        <div class="form-group mb-3">
                            <label for="name">{{ __("name") }}</label>
                            <input type="text" id="name" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="amount">{{ __("amount") }}</label>
                            <input type="number" id="amount" name="amount" class="form-control">
                            <small class="text-danger d-none"></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("close") }}</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check2"></i> {{ __("send") }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
      </div>

    @push("scripts")
        <script src="{{ asset("assets/js/stockist/transfer-wallet.js") }}"></script>
    @endpush
</x-layout.stockist>
