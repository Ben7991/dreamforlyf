<x-layout.admin>
    <x-slot name="title">Qualified Pool</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-2 mb-md-0">{{ __("qualified_pool_details") }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("qualified_pool") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("details") }}</li>
            </ol>
          </nav>
    </div>

    @php $goBackHeading = __("qualified_pool") @endphp
    <x-go-back path="/{{ App::currentLocale() }}/admin/qualified-pool" :title="$goBackHeading" />

    <div class="card mb-3 mb-xxl-4">
        <div class="card-header p-3 bg-white">
            <h5 class="m-0">{{ __("details") }}</h5>
        </div>
        <div class="card-body">
            <form action="/{{ App::currentLocale() }}/admin/qualified-pool/{{ $poolRecord->id }}/award" method="POST">
                @csrf
                @method("PUT")

                <div class="row">
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="date_time">{{ __("date_time") }}</label>
                        <input type="datetime" id="date_time" class="form-control" value="{{ $poolRecord->created_at }}" readonly>
                    </div>
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="distributor_id">{{ __("distributor_id") }}</label>
                        <input type="text" id="distributor_id" class="form-control" value="{{ $poolRecord->upline->user->id }}" readonly>
                    </div>
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="distributor">{{ __("distributor") }}</label>
                        <input type="text" id="distributor" class="form-control" value="{{ $poolRecord->upline->user->name }}" readonly>
                    </div>
                </div>

                @if($poolRecord->status === "PENDING")
                    <button class="btn btn-success" type="submit">
                        <i class="bi bi-check2"></i> {{ __('award') }}
                    </button>
                @endif
            </form>
        </div>
    </div>

    {{-- <div class="card mb-3 mb-xxl-4">
        <div class="card-header p-3 bg-white">
            <h5 class="m-0">{{ __("products") }}</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover display" id="product-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __("product") }}</th>
                        <th>{{ __("quantity") }}</th>
                        <th>{{ __("price") }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->pivot->quantity }}</td>
                            <td>${{ $product->price }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div> --}}

</x-layout.admin>
