<x-layout.admin>
    <x-slot name="title">Order History</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-2 mb-md-0">{{ __("order_details") }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin/order-history">{{ __("order_history") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("order_details") }}</li>
            </ol>
          </nav>
    </div>

    @php $goBackHeading = __("order_history") @endphp
    <x-go-back path="/{{ App::currentLocale() }}/admin/order-history" :title="$goBackHeading" />

    <div class="card mb-3 mb-xxl-4">
        <div class="card-header p-3 bg-white">
            <h5 class="m-0">#{{ $order->id }}</h5>
        </div>
        <div class="card-body">
            <form action="/{{ App::currentLocale() }}/admin/order-history/{{ $order->id }}" method="POST">
                @csrf
                @method("PUT")
                <div class="row">
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="date_time">{{ __("date_time") }}</label>
                        <input type="datetime" id="date_time" class="form-control" value="{{ $order->date_added }}" readonly>
                    </div>
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="amount">{{ __("amount") }}</label>
                        <input type="text" id="amount" class="form-control" value="$ {{ $order->amount }}" readonly>
                    </div>
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="distributor">{{ __("distributor") }}</label>
                        <input type="text" id="distributor" class="form-control" value="{{ $order->distributor->user->name }}" readonly>
                    </div>
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="order_type">{{ __("order_type") }}</label>
                        <input type="text" id="order_type" class="form-control" value="{{ $order->order_type }}" readonly>
                    </div>
                    @if($order->stockist->id !== 1)
                        <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                            <label for="status">{{ __("status") }}</label>
                            <input type="text"  class="form-control" value="{{ $order->status }}" readonly>
                        </div>

                        <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                            <label for="transfer_products">Transfer products to stockist</label>
                            @if ($order->is_transferred)
                                <input type="text"  class="form-control" value="YES" readonly>
                            @else
                                <select name="transfer_products" id="transfer_products" class="form-select">
                                    <option value="NO">No</option>
                                    <option value="YES">Yes</option>
                                </select>
                            @endif
                        </div>
                    @else
                        <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                            <label for="status">{{ __("status") }}</label>
                            <select name="status" id="status" class="form-select">
                                <option value="PENDING" {{ $order->status === "PENDING" ? 'selected' : "" }}>{{ strtoupper(__("pending")) }}</option>
                                <option value="APPROVED" {{ $order->status === "APPROVED" ? 'selected' : "" }}>{{ strtoupper(__("approved")) }}</option>
                            </select>
                        </div>
                    @endif
                </div>
                @if($order->status === "PENDING")
                    <button class="btn btn-success" type="submit">
                        <i class="bi bi-save"></i> {{ __('save') }}
                    </button>
                @endif
            </form>
        </div>
    </div>

    <div class="card mb-3 mb-xxl-4">
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
    </div>

</x-layout.admin>
