<x-layout.distributor>
    <x-slot name="title">Order History</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3 mb-xxl-4">
        <h4 class="mb-2 mb-md-0">
            {{ __("order_details") }}
        </h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/order-history">{{ __("order_history") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("order_details") }}</li>
            </ol>
        </nav>
    </div>

    @php $goBackHeading = __("order_history") @endphp
    <x-go-back path="/{{ App::currentLocale() }}/distributor/order-history" :title="$goBackHeading"/>

    <div class="card border shadow-sm mb-3 mb-xxl-4">
        <div class="card-header bg-white p-3">
            <h5 class="mb-2 mb-md-0">#{{ $order->id }}</h5>
        </div>
        <div class="card-body p-3">
            <div class="row">
                <div class="col-12 col-md-4 col-xl-3 mb-3">
                    <label for="date_added">{{ __("date_time") }}</label>
                    <input type="datetime" name="date_added" id="date_added" class="form-control" value="{{ $order->date_added }}" readonly>
                </div>
                <div class="col-12 col-md-4 col-xl-3 mb-3">
                    <label for="amount">{{ __("amount") }}</label>
                    <input type="text" name="amount" id="amount" class="form-control" value="$ {{ $order->amount }}" readonly>
                </div>
                <div class="col-12 col-md-4 col-xl-3 mb-3">
                    <label for="order_type">{{ __("order_type") }}</label>
                    <input type="text" id="order_type" class="form-control" value="{{ $order->order_type }}" readonly>
                </div>
                <div class="col-12 col-md-4 col-xl-3 mb-3">
                    <label for="status">{{ __("status") }}</label>
                    <input type="text" id="status" class="form-control" name="status" value="{{ $order->status }}" readonly>
                </div>
            </div>
        </div>
    </div>

    <div class="card border shadow-sm">
        <div class="card-header bg-white p-3">
            <h5 class="mb-2 mb-md-0">{{ __("products") }}</h5>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover display align-middle" id="product-table">
                    <thead>
                        <tr>
                            <th>{{ __("product") }}</th>
                            <th>{{ __("quantity") }}</th>
                            <th>{{ __("price") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orderItems as $item)
                            <tr>
                                <td>
                                    <img src="{{ asset(str_replace("public", "storage", $item["product"]->image)) }}" alt="Product Image" class="table-img">
                                    {{ $item["product"]->name }}
                                </td>
                                <td>${{ $item["quantity"] }}</td>
                                <td>{{ $item["product"]->price * $item["quantity"] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push("scripts")
        <script>
            $(document).ready(function() {
                $("#product-table").DataTable();
            });
            <script src="{{ asset("assets/js/distributor/reset-maintenance-store.js") }}"></script>
        </script>
    @endpush
</x-layout.distributor>
