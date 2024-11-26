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

    <x-analytics-navbar activePage='upgrade-bonus'/>

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
        $prices = $bvs = $quantities = $bonusCommissions = $ceoProduct = $ceoMoney = $orderProducts = $total = 0;
        $price = $bv = 0;
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
                            <th>Total</th>
                            <th>Package Types</th>
                            <th>Price</th>
                            <th>Package BVs</th>
                            <th>Bonus Commission(58.5%)</th>
                            <th>Money of Product and CEO</th>
                            <th>Coefficient</th>
                            <th>Quantity</th>
                            <th>CEO's Money and others charges</th>
                            <th>Money for Ordering Products</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($packages as $package)
                            <tr>
                                <td>
                                    {{ $package['total'] }}
                                    @php $total += $package['total']; @endphp
                                </td>
                                <td>{{ Str::replaceArray('Club', ['', ''], $package['package']) }}</td>
                                <td>
                                    ${{ $package['price'] * $package['total'] }}
                                    @php
                                        $price = $package['price'] * $package['total'];
                                        $prices += $price;
                                    @endphp
                                </td>
                                <td>
                                    {{ $package['bv'] * $package['total'] }}
                                    @php
                                        $bv = $package['bv'] * $package['total'];
                                        $bvs += $bv;
                                    @endphp
                                </td>
                                <td>
                                    ${{ number_format($bv * 0.585, 2) }}
                                    @php $bonusCommissions += $bv * 0.585; @endphp
                                </td>
                                <td>
                                    ${{ number_format($price - ($bv * 0.585), 2) }}
                                    @php $ceoProduct += $price - ($bv * 0.585); @endphp
                                </td>
                                <td>12</td>
                                <td>
                                    {{ $package['quantity'] * $package['total'] }}
                                    @php $quantities += $package['quantity'] * $package['total']; @endphp
                                </td>
                                <td>
                                    ${{ $package['quantity'] * $package['total'] * 12 }}
                                    @php $ceoMoney += $package['quantity'] * $package['total'] * 12; @endphp
                                </td>
                                <td>
                                    ${{ number_format(($price - ($bv * 0.585)) - ($package['quantity'] * $package['total'] * 12), 2) }}
                                    @php $orderProducts += ($price - ($bv * 0.585)) - ($package['quantity'] * $package['total'] * 12) @endphp
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td>{{ $total }}</td>
                            <td></td>
                            <td>${{ $prices }}</td>
                            <td>{{ $bvs }}</td>
                            <td>${{ number_format($bonusCommissions, 2) }}</td>
                            <td>${{ number_format($ceoProduct, 2) }}</td>
                            <td></td>
                            <td>{{ $quantities }}</td>
                            <td>${{ number_format($ceoMoney, 2) }}</td>
                            <td>${{ number_format($orderProducts, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border shadow-sm mt-4">
        <div class="card-header bg-white p-3">
            <h5 class="m-0">General Assessment For Upgrade Bonus</h5>
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
                            <td>{{ $quantities }}</td>
                            <td>2</td>
                            <td>{{ $quantities * 2 }}</td>
                        </tr>
                        <tr>
                            <td>CEO</td>
                            <td>{{ $quantities }}</td>
                            <td>2</td>
                            <td>{{ $quantities * 2 }}</td>
                        </tr>
                        <tr>
                            <td>Awards</td>
                            <td>{{ $quantities }}</td>
                            <td>2</td>
                            <td>{{ $quantities * 2 }}</td>
                        </tr>
                        <tr>
                            <td>Reserve</td>
                            <td>{{ $quantities }}</td>
                            <td>2</td>
                            <td>{{ $quantities * 2 }}</td>
                        </tr>
                        <tr>
                            <td>Leadership Bonus</td>
                            <td>{{ $quantities }}</td>
                            <td>1</td>
                            <td>{{ $quantities * 1 }}</td>
                        </tr>
                        <tr>
                            <td>Pool bonus</td>
                            <td>{{ $quantities }}</td>
                            <td>1</td>
                            <td>{{ $quantities * 1 }}</td>
                        </tr>
                        <tr>
                            <td>Head office stockist</td>
                            <td>{{ $quantities }}</td>
                            <td>1</td>
                            <td>{{ $quantities * 1 }}</td>
                        </tr>
                        <tr>
                            <td>NGO</td>
                            <td>{{ $quantities }}</td>
                            <td>1</td>
                            <td>{{ $quantities * 1 }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{ ($quantities * 2 * 4) + ($quantities * 1 * 4) }}</td>
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
            $(document).ready(function() {
                const datePicker = $("#datePicker");
                const token = document.querySelector("#token");
                let startDate = "", endDate = "";

                datePicker.daterangepicker(
                    null,
                    function(start, end, label) {
                        startDate = start.format("YYYY-MM-DD") + " 00:00:00";
                        endDate = end.format("YYYY-MM-DD") + " 00:00:00";
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
