<x-layout.admin>
    <x-slot name="title">Distributors</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-2 mb-md-0">{{ $user->name }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin/distributors">{{ __("distributors") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __('details') }}</li>
            </ol>
          </nav>
    </div>

    @php $goBackHeading = __("distributors"); @endphp
    <x-go-back path="/{{ App::currentLocale() }}/admin/distributors" :title="$goBackHeading" />

    <div class="card border shadow-sm mb-3 mb-xxl-4">
        <div class="card-header bg-white p-3">
            <h5 class="mb-2 mb-md-0">{{ __("personal_details") }}</h5>
        </div>
        <div class="card-body p-3">
            <form action="/{{ App::currentLocale() }}/admin/distributors/{{ $user->id }}" method="POST">
                @csrf
                @method("PUT")

                <div class="row">
                    <div class="col-12 col-md-6 col-xl-4 col-xxl-3 mb-3 mb-xxl-4">
                        <label class="text-secondary" for="id">ID</label>
                        <input type="text" id="id" class="form-control" value="{{ $user->id }}" readonly>
                    </div>
                    <div class="col-12 col-md-6 col-xl-4 col-xxl-3 mb-3 mb-xxl-4">
                        <label class="text-secondary" for="date_joined">{{ __("date_joined") }}</label>
                        <input type="text" id="date_joined" class="form-control" value="{{ $user->distributor->created_at }}" readonly>
                    </div>
                    <div class="col-12 col-md-6 col-xl-4 col-xxl-3 mb-3 mb-xxl-4">
                        <label class="text-secondary" for="name">{{ __("name") }}</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}">
                    </div>
                    <div class="col-12 col-md-6 col-xl-4 col-xxl-3 mb-3 mb-xxl-4">
                        <label class="text-secondary" for="email">{{ __("email") }}</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}">
                    </div>
                    <div class="col-12 col-md-6 col-xl-4 col-xxl-3 mb-3 mb-xxl-4">
                        <label class="text-secondary" for="upline">{{ __("upline") }}</label>
                        <input type="text" id="upline" class="form-control" value="{{ $user->distributor->upline->user->name }}" readonly>
                    </div>
                    <div class="col-12 col-md-6 col-xl-4 col-xxl-3 mb-3 mb-xxl-4">
                        <label class="text-secondary" for="country">{{ __("country") }}</label>
                        <input type="text" name="country" id="country" class="form-control" value="{{ $user->distributor->country }}">
                    </div>
                    <div class="col-12 col-md-6 col-xl-4 col-xxl-3 mb-3 mb-xxl-4">
                        <label class="text-secondary" for="city">{{ __("city") }}</label>
                        <input type="text" name="city" id="city" class="form-control" value="{{ $user->distributor->city }}">
                    </div>
                    <div class="col-12 col-md-6 col-xl-4 col-xxl-3 mb-3 mb-xxl-4">
                        <label class="text-secondary" for="phone_number">{{ __("phone_number") }}</label>
                        <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ $user->distributor->phone_number }}">
                    </div>
                    <div class="col-12 col-md-6 col-xl-4 col-xxl-3 mb-3 mb-xxl-4">
                        <label class="text-secondary" for="package">{{ __("membership_package") }}</label>
                        <input type="text" id="package" class="form-control" value="{{ $user->distributor->registrationPackage->name }}" readonly>
                    </div>
                    <div class="col-12 col-md-6 col-xl-4 col-xxl-3 mb-3 mb-xxl-4">
                        <label class="text-secondary" for="leg">{{ __("leg") }}</label>
                        <input type="text" id="leg" class="form-control" value="{{ $user->distributor->leg }}" readonly>
                    </div>
                    <div class="col-12 col-md-6 col-xl-4 col-xxl-3 mb-3 mb-xxl-4">
                        <label class="text-secondary" for="wave_number">{{ __("wave_number") }}</label>
                        <input type="text" name="wave_number" id="wave_number" class="form-control" value="{{ $user->distributor->wave }}">
                    </div>
                    <div class="col-12 col-md-6 col-xl-4 col-xxl-3 mb-3 mb-xxl-4">
                        <label class="text-secondary" for="wave_number">Action</label>
                        <select name="action" id="action" class="form-select">
                            <option value="active" {{ $user->status === "active" ? "selected" : "" }}>{{ __("active") }}</option>
                            <option value="suspend" {{ $user->status === "suspend" ? "selected" : "" }}>{{ __("suspend") }}</option>
                        </select>
                    </div>
                </div>

                <button class="btn btn-success" type="submit">
                    <i class="bi bi-save"></i> {{ __("save") }}
                </button>
            </form>
        </div>
    </div>

    <div class="card border shadow-sm mb-3 mb-xxl-4">
        <div class="card-header bg-white d-block d-md-flex align-items-center justify-content-between p-3">
            <h5 class="mb-2 mb-md-0">{{ __("portfolio_details") }}</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-main" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="bi bi-wallet"></i> {{ __("transfer_wallet") }}
                </button>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __("current_balance") }}</th>
                            <th>{{ __("personal_wallet") }}</th>
                            <th>{{ __("commission_wallet") }}</th>
                            <th>{{ __("leadership_wallet") }}</th>
                            <th>{{ __("total_withdrawal_wallet") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>${{ number_format($user->distributor->portfolio->current_balance, 2) }}</td>
                            <td>${{ number_format($personalWallet, 2) }}</td>
                            <td>${{ number_format($user->distributor->portfolio->commission_wallet, 2) }}</td>
                            <td>${{ number_format($leadershipWallet, 2) }}</td>
                            <td>${{ number_format($totalWithdrawals, 2) }}</td>
                        </tr>
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
                <form action="/{{ App::currentLocale() }}/admin/distributors/{{ $user->id }}/wallet" method="post">
                    @csrf
                    @method("POST")

                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="wallet">{{ __("amount") }}</label>
                            <input type="text" class="form-control" id="wallet" name="wallet">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("close") }}</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-send"></i> {{ __("transfer_wallet") }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @push("scripts")
        <script src="{{ asset("assets/js/admin/distributor/create.js") }}"></script>
    @endpush
</x-layout.admin>
