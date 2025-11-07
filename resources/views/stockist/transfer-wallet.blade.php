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
                            @if($isHeadStockist)
                                <th>{{ __("status") }}</th>
                                <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transfers as $transfer)
                            <tr>
                                <td>{{ $transfer->date_added }}</td>
                                <td>{{ $transfer->stockist_id }}</td>
                                <td>{{ $transfer->name }}</td>
                                <td>${{ number_format($transfer->amount, 2) }}</td>
                                @if($isHeadStockist)
                                    <td>
                                        @if($transfer->status === "REVERSED")
                                            <span class="badge text-bg-secondary">{{ $transfer->status }}</span>
                                        @else
                                            <span class="badge text-bg-success">{{ $transfer->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($transfer->status === "COMPLETE")
                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="{{ __("reverse_transfer") }}">
                                                <button class="action-btn text-primary rounded" type="button" data-bs-toggle="modal" data-bs-target="#transferReversal"
                                                onclick="setFormAction('/{{App::getLocale()}}/stockist/transfer-wallet/{{ $transfer->id }}/reverse')">
                                                    <i class="bi bi-arrow-90deg-left"></i>
                                                </button>
                                            </span>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="transferReversal" tabindex="-1" aria-labelledby="transferReversalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="transferReversalLabel">{{ __("reverse_transfer") }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <input type="hidden" id="locale" value="{{ App::currentLocale() }}">
                <form method="POST" id="form">
                    @csrf

                    <div class="modal-body">
                        <p class="mb-3">{{ __("reverse_transfer_desc") }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("close") }}</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check2"></i> {{ __("reverse_transfer") }}
                        </button>
                    </div>
                </form>
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
                <form method="POST" id="transfer-form">
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
        <script>
            $(document).ready(function () {
                $("#product-table").DataTable();
            });

            const amount = document.querySelector("#amount");
            let isAmountValidated = false;
            let amountError = "Amount field is required";
            amount.addEventListener("change", function () {
                const value = this.value;

                if (value === "") {
                    isAmountValidated = false;
                    amountError = "Amount field is required";
                } else if (!/^[0-9]+(\.[0-9]{2})*$/.test(value)) {
                    isAmountValidated = false;
                    amountError = "Only digits are allowed";
                } else {
                    isAmountValidated = true;
                    amountError = "";
                }

                checkInput(amount, isAmountValidated, amountError);
            });

            const transferForm = document.querySelector("#transfer-form");
            transferForm.addEventListener("submit", function (event) {
                if (!isAmountValidated) {
                    event.preventDefault();

                    checkInput(amount, isAmountValidated, amountError);
                }
            });

            const nameInput = document.querySelector("#name");
            const searchInput = document.querySelector("#search");
            const locale = document.querySelector("#locale");
            const loader = document.querySelector(".loader");
            const searchError = document.querySelector("#search-error");

            const btnSearch = document.querySelector("#search-btn");
            btnSearch.addEventListener("click", function () {
                const searchTerm = searchInput.value;

                if (searchTerm === "") {
                    return;
                }

                loader.classList.remove("d-none");
                searchInput.value = "";
                !searchError.classList.contains("d-none")
                    ? searchError.classList.add("d-none")
                    : null;

                $.ajax({
                    url: `/users/${searchTerm}`,
                    method: "GET",
                    success: function (data, xhr, status) {
                        nameInput.value = data.data.name;
                        transferForm.action = `/${locale.value}/stockist/transfer-wallet/${data.data.id}`;
                        loader.classList.add("d-none");
                    },
                    error: function (xhr, status, error) {
                        loader.classList.add("d-none");
                        searchError.classList.remove("d-none");
                    },
                });
            });
        </script>
    @endpush
</x-layout.stockist>
