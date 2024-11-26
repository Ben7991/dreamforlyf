<x-layout.admin>
    <x-slot name="title">Analytics</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-2 mb-md-0">{{ __("analytics") }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("analytics") }}</li>
            </ol>
        </nav>
    </div>

    <x-analytics-navbar activePage='personal-purchase'/>

    @php
        $totalProductCo = $totalBinaryCo = $totalCashBackCo = 0;
    @endphp

    <div class="d-flex justify-content-end mb-3">
        <div class="d-flex align-items-center gap-2">
            <div>
                <input type="text" id="datePicker" class="form-select">
                <input type="hidden" id="token" value="{{ csrf_token() }}">
            </div>
            <div class="spinner-border d-none" role="status" id="spinner">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    @php
        $totalReorder = $totalPrice = $totalQuantity = $totalAmount = $totalProductCos = $totalBinaryCos = $totalProductsCo = $totalCashBackCos = $totalSummary = $totalCEO = 0;
    @endphp

    <div class="card border shadow-sm">
        <div class="card-header bg-white p-3">
            <h5 class="m-0">Overall History</h5>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover display" id="product-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Total Product Prices</th>
                            <th>Product Co-efficient</th>
                            <th>Binary Co-efficient</th>
                            <th>Cash back Co-efficient</th>
                            <th>Total Products Co-efficient</th>
                            <th>Total Binary Co-efficient</th>
                            <th>Total Cash Back Co-efficient</th>
                            <th>Summary</th>
                            <th>CEO's Money and others charges</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($result as $data)
                            <tr>
                                <td>{{ $data->name }}</td>
                                <td>
                                    ${{ number_format($data->price, 2) }}
                                    @php $totalPrice += $data->price; @endphp
                                </td>
                                <td>
                                    {{ $data->quantity }}
                                    @php $totalQuantity += $data->quantity; @endphp
                                </td>
                                <td>
                                    ${{ number_format($data->price * $data->quantity, 2) }}
                                    @php $totalAmount += $data->price * $data->quantity; @endphp
                                </td>
                                <td>
                                    @php
                                        $productCo = 0;

                                        if($data->price == 27) $productCo = 9;
                                        elseif ($data->price == 30) $productCo = 12;
                                        else $productCo = 15;

                                        $totalProductCos += $productCo;
                                    @endphp
                                    {{ $productCo }}
                                </td>
                                <td>4</td>
                                <td>2</td>
                                <td>
                                    @if($data->price == 27)
                                        @php $totalProductCo = 9 * $data->quantity; @endphp
                                    @elseif ($data->price == 30)
                                        @php $totalProductCo = 12 * $data->quantity; @endphp
                                    @else
                                        @php $totalProductCo = 15 * $data->quantity; @endphp
                                    @endif

                                    ${{ number_format($totalProductCo, 2) }}
                                    @php $totalProductsCo += $totalProductCo; @endphp
                                </td>
                                <td>
                                    @php
                                        $totalBinaryCo = 4 * $data->quantity;
                                        $totalBinaryCos += $totalBinaryCo;
                                    @endphp
                                    ${{ number_format($totalBinaryCo, 2) }}
                                </td>
                                <td>
                                    @php
                                        $totalCashBackCo = 2 * $data->quantity;
                                        $totalCashBackCos += $totalCashBackCo;
                                    @endphp
                                    ${{ number_format($totalCashBackCo, 2) }}
                                </td>
                                <td>
                                    ${{ number_format($totalProductCo + $totalBinaryCo + $totalCashBackCo, 2) }}
                                    @php $totalSummary += ($totalProductCo + $totalBinaryCo + $totalCashBackCo); @endphp
                                </td>
                                <td>
                                    ${{ number_format(($data->price * $data->quantity) - ($totalProductCo + $totalBinaryCo + $totalCashBackCo), 2) }}
                                    @php $totalCEO += ($data->price * $data->quantity) - ($totalProductCo + $totalBinaryCo + $totalCashBackCo); @endphp
                                </td>
                            </tr>
                        @endforeach

                            <tr>
                                <td></td>
                                <td>${{ number_format($totalPrice, 2) }}</td>
                                <td>{{ $totalQuantity }}</td>
                                <td>${{ number_format($totalAmount, 2) }}</td>
                                <td>{{ $totalProductCos }}</td>
                                <td>{{ count($result) * 4 }}</td>
                                <td>{{ count($result) * 2 }}</td>
                                <td>${{ number_format($totalProductsCo, 2) }}</td>
                                <td>${{ number_format($totalBinaryCos, 2) }}</td>
                                <td>${{ number_format($totalCashBackCos, 2) }}</td>
                                <td>${{ number_format($totalSummary, 2) }}</td>
                                <td>${{ number_format($totalCEO, 2) }}</td>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @php
        $finalTotalGeneralProduct = 0;
    @endphp

    <div class="card border shadow-sm mt-4">
        <div class="card-header bg-white p-3">
            <h5 class="m-0">General Assessment For Reorder/Personal Purchase</h5>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Designation</th>
                            <th>General Total Products Quantity</th>
                            <th>Product Price Co-Efficient</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Company</td>
                            <td>{{ $totalGeneralProductQuantity }}</td>
                            <td>2</td>
                            <td>
                                @php
                                    $finalTotalGeneralProduct += ($totalGeneralProductQuantity * 2)
                                @endphp
                                {{ $totalGeneralProductQuantity * 2 }}
                            </td>
                        </tr>
                        <tr>
                            <td>CEO</td>
                            <td>{{ $totalGeneralProductQuantity }}</td>
                            <td>2</td>
                            <td>
                                @php
                                    $finalTotalGeneralProduct += ($totalGeneralProductQuantity * 2)
                                @endphp
                                {{ $totalGeneralProductQuantity * 2 }}
                            </td>
                        </tr>
                        <tr>
                            <td>Awards</td>
                            <td>{{ $totalGeneralProductQuantity }}</td>
                            <td>2</td>
                            <td>
                                @php
                                    $finalTotalGeneralProduct += ($totalGeneralProductQuantity * 2)
                                @endphp
                                {{ $totalGeneralProductQuantity * 2 }}
                            </td>
                        </tr>
                        <tr>
                            <td>Reserve</td>
                            <td>{{ $totalGeneralProductQuantity }}</td>
                            <td>2</td>
                            <td>
                                @php
                                    $finalTotalGeneralProduct += ($totalGeneralProductQuantity * 2)
                                @endphp
                                {{ $totalGeneralProductQuantity * 2 }}
                            </td>
                        </tr>
                        <tr>
                            <td>Leadership bonus</td>
                            <td>{{ $totalGeneralProductQuantity }}</td>
                            <td>1</td>
                            <td>
                                @php
                                    $finalTotalGeneralProduct += $totalGeneralProductQuantity
                                @endphp
                                {{ $totalGeneralProductQuantity }}
                            </td>
                        </tr>
                        <tr>
                            <td>Pool bonus</td>
                            <td>{{ $totalGeneralProductQuantity }}</td>
                            <td>1</td>
                            <td>
                                @php
                                    $finalTotalGeneralProduct += $totalGeneralProductQuantity
                                @endphp
                                {{ $totalGeneralProductQuantity }}
                            </td>
                        </tr>
                        <tr>
                            <td>Head office stockist</td>
                            <td>{{ $totalGeneralProductQuantity }}</td>
                            <td>1</td>
                            <td>
                                @php
                                    $finalTotalGeneralProduct += $totalGeneralProductQuantity
                                @endphp
                                {{ $totalGeneralProductQuantity }}
                            </td>
                        </tr>
                        <tr>
                            <td>NGO</td>
                            <td>{{ $totalGeneralProductQuantity }}</td>
                            <td>1</td>
                            <td>
                                @php
                                    $finalTotalGeneralProduct += $totalGeneralProductQuantity
                                @endphp
                                {{ $totalGeneralProductQuantity }}
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Total: {{ $finalTotalGeneralProduct }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push("scripts")
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <script>
            $(document).ready(function(){
                const datePicker = $("#datePicker");
                const token = document.querySelector("#token");
                const table = document.querySelector("#product-table");
                let startDate = "", endDate = "";

                datePicker.daterangepicker(
                    null,
                    function(start, end, label) {
                        startDate = start.format("YYYY-MM-DD");
                        endDate = end.format("YYYY-MM-DD");
                    }
                );
                datePicker.on("apply.daterangepicker", function(ev, picker){
                    const searchParams = new URLSearchParams();
                    searchParams.set("startDate", startDate);
                    searchParams.set("endDate", endDate);

                    let location = window.location.origin + window.location.pathname;

                    let newLocation = location + "?" +searchParams.toString();
                    window.location.href = newLocation;
                });
            });
        </script>
    @endpush
</x-layout.admin>
