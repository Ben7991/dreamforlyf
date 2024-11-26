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

    <x-analytics-navbar activePage='maintenance'/>

    @php
        $totalProductCo = $totalBinaryCo = $binaryMoney = $productCoEfficient = 0;
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
        $totalCounts = $totalQuantity = $totalPrice = $totalProductMoney = $totalBinaryMoney = $totalCEOMoney = $totalSummary = $totalComments = 0;
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
                            <th>BV</th>
                            <th>Qty</th>
                            <th>Total Prices</th>
                            <th>P. Co-efficient</th>
                            <th>Binary(12.5%)</th>
                            <th>Total Money of the Products</th>
                            <th>Binary Money</th>
                            <th>CEO's Money and others charges</th>
                            <th>Summary</th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($result as $data)
                            <tr>
                                <td>{{ $data->name }}</td>
                                <td>
                                    ${{ number_format($data->price, 2) }}
                                </td>
                                <td>{{ $data->bv_point * $data->quantity }}</td>
                                <td>
                                    {{ $data->quantity }}
                                    @php
                                        $totalQuantity += $data->quantity;
                                    @endphp
                                </td>
                                <td>
                                    ${{ number_format($data->price * $data->quantity, 2) }}
                                    @php
                                        $totalPrice += $data->price * $data->quantity;
                                    @endphp
                                </td>
                                <td>
                                    @if($data->price == 27)
                                        @php $productCoEfficient = 9; @endphp
                                    @elseif ($data->price == 30)
                                        @php $productCoEfficient = 12; @endphp
                                    @else
                                        @php $productCoEfficient = 15; @endphp
                                    @endif
                                    {{ $productCoEfficient }}
                                </td>
                                <td>12.5%</td>
                                <td>
                                    @php
                                        $totalProductMoney += $data->quantity * $productCoEfficient;
                                        $productMoney = $data->quantity * $productCoEfficient;
                                    @endphp
                                    ${{ number_format($productMoney, 2) }}
                                </td>
                                <td>
                                    @php
                                        $binaryMoney = 0.125 * $data->bv_point * $data->quantity;
                                        $totalBinaryMoney += $binaryMoney;
                                    @endphp
                                    ${{ $binaryMoney  }}
                                </td>
                                <td>
                                    ${{ 12 * $data->quantity}}
                                    @php
                                        $ceoMoney = (12 * $data->quantity);
                                        $totalCEOMoney += $ceoMoney;
                                    @endphp
                                </td>
                                <td>
                                    @php
                                        $summary = $ceoMoney + $binaryMoney + $productMoney;
                                        $totalSummary += $summary;
                                    @endphp
                                    ${{ $summary }}
                                </td>
                                <td>
                                    ${{ ($data->price * $data->quantity) - $summary }}
                                    @php
                                        $totalComments += ($data->price * $data->quantity) - $summary;
                                    @endphp
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{ $totalQuantity }}</td>
                            <td>${{ $totalPrice }}</td>
                            <td></td>
                            <td></td>
                            <td>${{ number_format($totalProductMoney, 2) }}</td>
                            <td>${{ $totalBinaryMoney }}</td>
                            <td>${{ $totalCEOMoney }}</td>
                            <td>${{ $totalSummary }}</td>
                            <td>${{ $totalComments }}</td>
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
            <h5 class="m-0">General Assessment For Maintenance</h5>
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
