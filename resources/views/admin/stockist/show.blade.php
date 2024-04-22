<x-layout.admin>
    <x-slot name="title">Stockists</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-2 mb-md-0">{{ __("details") }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin/stockists">{{ __("stockists") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("details") }}</li>
            </ol>
          </nav>
    </div>

    @php $goBackHeading = __("details") @endphp
    <x-go-back path="/{{ App::currentLocale() }}/admin/stockists" :title="$goBackHeading" />

    <div class="container-fluid p-3 bg-white rounded border shadow-sm mb-4">
        <h5 class="mb-3 mb-md-0">{{ __("details") }}</h5>

        <ul>
            @foreach($errors->all() as $message)
                <li class="text-danger">{{ $message }}</li>
            @endforeach
        </ul>

        <form action="/{{ App::currentLocale() }}/admin/stockists/{{ $user->id }}" method="POST" id="form">
            @csrf
            @method("PUT")

            <div class="row mb-3">
                <div class="col-12 col-md-6 col-lg-4 col-xxl-3 mb-3">
                    <label for="name">{{ __("name") }}</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}">
                    <small class="text-danger d-none"></small>
                </div>
                <div class="col-12 col-md-6 col-lg-4 col-xxl-3 mb-3">
                    <label for="email">{{ __("email") }}</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}">
                    <small class="text-danger d-none"></small>
                </div>
                <div class="col-12 col-md-6 col-lg-4 col-xxl-3 mb-3">
                    <label for="country">{{ __("country") }}</label>
                    <input type="text" name="country" id="country" class="form-control" value="{{ $user->stockist->country }}">
                    <small class="text-danger d-none"></small>
                </div>
                <div class="col-12 col-md-6 col-lg-4 col-xxl-3 mb-3">
                    <label for="city">{{ __("city") }}</label>
                    <input type="text" name="city" id="city" class="form-control" value="{{ $user->stockist->city }}">
                    <small class="text-danger d-none"></small>
                </div>
                <div class="col-12 col-md-6 col-lg-4 col-xxl-3 mb-3">
                    <label for="code">Code</label>
                    <input type="text" name="code" id="code" class="form-control" value="{{ $user->stockist->code }}">
                    <small class="text-danger d-none"></small>
                </div>
                <div class="col-12 col-md-6 col-lg-4 col-xxl-3 mb-3">
                    <label for="wallet">Wallet</label>
                    <input type="text" readonly id="wallet" class="form-control" value="{{ $user->stockist->wallet }}">
                    <small class="text-danger d-none"></small>
                </div>
            </div>

            <button type="submit" class="btn btn-success btn-submit">
                <span class="main-btn">
                    <i class="bi bi-save"></i> {{ __("save") }}
                </span>
                <x-submit-spinner />
            </button>
        </form>
    </div>


    <div class="card border shadow-sm">
        <div class="card-header bg-white d-block d-md-flex align-items-center justify-content-between p-3">
            <h5 class="mb-2 mb-md-0">{{ __("available") }}</h5>

            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="bi bi-send"></i> {{ __("transfer_wallet") }}
            </button>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover display" id="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __("date_time") }}</th>
                            <th>{{ __("amount") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transfers as $tranfer)
                            <tr>
                                <td>{{ $tranfer->id }}</td>
                                <td>{{ $tranfer->date_added }}</td>
                                <td>{{ $tranfer->amount }}</td>
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
                <form action="/{{ App::currentLocale() }}/admin/stockists/{{ $user->id }}/transfer-wallet"
                    id="transfer-form" method="POST">
                    @csrf

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="text" name="amount" id="amount" class="form-control">
                            <small class="text-danger d-none"></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("close") }}</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> {{ __("save") }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @push("scripts")
        <script src="{{ asset("assets/js/admin/stockist/edit.js") }}"></script>
        <script>
            $(document).ready(function() {
                $("#table").DataTable();
            });
        </script>
    @endpush
</x-layout.admin>
