<x-layout.distributor>
    <x-slot name="title">Transaction History</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-2 mb-md-0">{{ __("transaction_history") }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/distributor">{{ __("home") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("transaction_history") }}</li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid p-0 mb-3">
        <div class="row">
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $firstHeading = __("all"); @endphp
                <x-model-summary :title="$firstHeading" icon="list" :number="$transactionCount" class="bg-main" />
            </div>
        </div>
    </div>

    <div class="card border shadow-sm">
        <div class="card-header bg-white p-3">
            <h5 class="m-0">{{ __("available") }}</h5>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover display" id="product-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __("date_time") }}</th>
                            <th>{{ __("amount") }}</th>
                            <th>{{ __("portfolio") }}</th>
                            <th>{{ __("type") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->id }}</td>
                                <td>{{ $transaction->created_at }}</td>
                                <td>${{ number_format($transaction->amount, 2) }}</td>
                                <td>{{ str_replace("_", " ", $transaction->portfolio) }}</td>
                                <td>{{ str_replace("_", " ", $transaction->transaction_type) }}</td>
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Request Withdrawal</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/{{ App::currentLocale() }}/distributor/portfolio/commission/request" method="post" id="form">
                    @csrf
                    @method("POST")

                    <div class="modal-body">
                        <input type="hidden" value="{{ Auth::user()->distributor->portfolio->commission_wallet }}" id="wallet">
                        <div class="form-group mb-2">
                            <label for="package_id">Amount</label>
                            <input type="text" name="amount" id="amount" class="form-control">
                            <small class="text-danger d-none"></small>
                        </div>
                        <p>
                            <i class="bi bi-info-circle"></i> Every withdrawal attracts 5% deduction on the amount to withdrawal
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check2"></i> Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push("scripts")
        <script src="{{ asset("assets/js/distributor/portfolio/commission.js") }}"></script>

        <script>
            $(document).ready(function() {
                $("#product-table").DataTable();
            });
        </script>

        <script src="{{ asset("assets/js/distributor/reset-maintenance-store.js") }}"></script>
    @endpush
</x-layout.distributor>
