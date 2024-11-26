<x-layout.stockist>
    <x-slot name="title">Order History</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-2 mb-md-0">{{ __("order_history") }}</h4>

        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __("order_history") }}</li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid p-0 mb-3">
        <div class="row">
            <div class="col-12 col-md-4 col-xxl-3 mb-2 mb-md-0">
                @php $firstHeading = __("available"); @endphp
                <x-model-summary :title="$firstHeading" icon="list-ol" :number="$total" class="bg-main" />
            </div>
            <div class="col-12 col-md-4 col-xxl-3 mb-2 mb-md-0">
                @php $secondHeading = __("pending"); @endphp
                <x-model-summary :title="$secondHeading" icon="clock-history" :number="$pending" class="bg-tertiary" />
            </div>
            <div class="col-12 col-md-4 col-xxl-3 mb-2 mb-md-0">
                @php $thirdHeading = __("approved") @endphp
                <x-model-summary :title="$thirdHeading" icon="check2-circle" :number="$approved" class="bg-other" />
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
                            <th>{{ __("date_time") }}</th>
                            <th>{{ __("distributor_id") }}</th>
                            <th>{{ __("distributor") }}</th>
                            <th>{{ __("amount") }}</th>
                            <th>{{ __("order_type") }}</th>
                            <th>{{ __("status") }}</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->date_added }}</td>
                                <td>{{ $order->distributor->user->id }}</td>
                                <td>{{ $order->distributor->user->name }}</td>
                                <td>${{ $order->amount }}</td>
                                <td>{{ $order->order_type !== "NORMAL" ? $order->order_type : "PERSONAL" }}</td>
                                <td>
                                    @if($order->status === "PENDING")
                                        <span class="badge text-bg-danger">{{ $order->status }}</span>
                                    @else
                                        <span class="badge text-bg-success">{{ $order->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="{{ __("view") }}">
                                        <a href="/{{App::currentLocale()}}/stockist/order-history/{{ $order->id }}" class="action-btn text-primary rounded">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </span>
                                </td>
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
        </script>
    @endpush
</x-layout.stockist>
