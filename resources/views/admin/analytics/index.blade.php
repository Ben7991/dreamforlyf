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

    <x-analytics-navbar activePage='registration'/>

    <div class="d-flex justify-content-end mb-3">
        <div class="d-flex align-items-center gap-2">
            <div>
                <input type="text" id="datePicker" class="form-select">
                <input type="hidden" id="token" value="{{ csrf_token() }}">
            </div>
        </div>
    </div>

    @php
        $totalCounts = $totalPrice = $totalBv = $totalBCommission = $totalProductCEO = $totalQuantity = $totalCEOAndOther = $totalOrderingMoney = 0;
    @endphp

    <div class="card border shadow-sm">
        <div class="card-header bg-white p-3">
            <h5 class="m-0">Overall History</h5>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover" id="product-table">
                    <thead>
                        <tr>
                            <th>Registration No.</th>
                            <th>{{ __("name") }}</th>
                            <th>{{ __("price") }}</th>
                            <th>BV Point</th>
                            <th>Bonuses Commission (58.5%)</th>
                            <th>Money of Products and CEO</th>
                            <th>Quantity</th>
                            <th>CEO's Money and other charges</th>
                            <th>Money for ordering products</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($result as $data)
                            <tr>
                                <td>
                                    {{ $data->total_number }}
                                    @php $totalCounts += $data->total_number; @endphp
                                </td>
                                <td>{{ $data->name }}</td>
                                <td>
                                    ${{ number_format($data->price * $data->total_number, 2) }}
                                    @php $totalPrice += ($data->price * $data->total_number); @endphp
                                </td>
                                <td>
                                    {{ number_format($data->bv_point * $data->total_number) }}
                                    @php $totalBv += ($data->bv_point * $data->total_number); @endphp
                                </td>
                                <td>
                                    @php
                                        $bonusCommission = ($data->bv_point * $data->total_number) * 0.585;
                                        $totalBCommission += $bonusCommission;
                                    @endphp
                                    ${{ number_format($bonusCommission, 2) }}
                                </td>
                                <td>
                                    @php
                                        $ceoandProductMoney = (($data->price * $data->total_number) - $bonusCommission);
                                        $totalProductCEO += $ceoandProductMoney;
                                    @endphp
                                    ${{ number_format($ceoandProductMoney, 2) }}
                                </td>
                                <td>
                                    {{ $data->quantity * $data->total_number }}
                                    @php
                                        $totalQuantity += $data->quantity * $data->total_number;
                                    @endphp
                                </td>
                                <td>
                                    @php
                                        $ceoAndOtherCharge = ($data->quantity * $data->total_number * 12);
                                        $totalCEOAndOther += $ceoAndOtherCharge;
                                    @endphp
                                    ${{ number_format($ceoAndOtherCharge, 2) }}
                                </td>
                                <td>
                                    @php
                                        $orderingProductMoney = (($data->price * $data->total_number) - $bonusCommission)  - $ceoAndOtherCharge;
                                        $totalOrderingMoney += $orderingProductMoney;
                                    @endphp
                                    ${{ number_format($orderingProductMoney, 2) }}
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td>{{ $totalCounts }}</td>
                            <td></td>
                            <td>${{ number_format($totalPrice, 2) }}</td>
                            <td>{{ $totalBv }}</td>
                            <td>${{ number_format($totalBCommission, 2) }}</td>
                            <td>${{ number_format($totalProductCEO, 2) }}</td>
                            <td>{{ $totalQuantity }}</td>
                            <td>${{ number_format($totalCEOAndOther, 2) }}</td>
                            <td>${{ number_format($totalOrderingMoney, 2) }}</td>
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
            <h5 class="m-0">General Assessment For Registrations</h5>
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
                                    $finalTotalGeneralProduct += ($totalGeneralProductQuantity)
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
                const spinner = $("#spinner");
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
