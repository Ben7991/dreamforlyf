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

    <x-analytics-navbar activePage='general-assessment'/>

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

    <div class="card border shadow-sm mt-4">
        <div class="card-header bg-white p-3">
            <h5 class="m-0">General Assessment</h5>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Designation</th>
                            <th>Total Registration</th>
                            <th>Total Upgrade</th>
                            <th>Total Reorder</th>
                            <th>Total Maintenance</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Company</td>
                            <td>{{ $registration['first'] }}</td>
                            <td>{{ $upgrade['first'] }}</td>
                            <td>{{ $personal['first'] }}</td>
                            <td>{{ $maintenance['first'] }}</td>
                            <td>
                                {{ $registration['first'] + $upgrade['first'] + $personal['first'] + $maintenance['first'] }}
                            </td>
                        </tr>
                        <tr>
                            <td>CEO</td>
                            <td>{{ $registration['first'] }}</td>
                            <td>{{ $upgrade['first'] }}</td>
                            <td>{{ $personal['first'] }}</td>
                            <td>{{ $maintenance['first'] }}</td>
                            <td>
                                {{ $registration['first'] + $upgrade['first'] + $personal['first'] + $maintenance['first'] }}
                            </td>
                        </tr>
                        <tr>
                            <td>Awards</td>
                            <td>{{ $registration['first'] }}</td>
                            <td>{{ $upgrade['first'] }}</td>
                            <td>{{ $personal['first'] }}</td>
                            <td>{{ $maintenance['first'] }}</td>
                            <td>
                                {{ $registration['first'] + $upgrade['first'] + $personal['first'] + $maintenance['first'] }}
                            </td>
                        </tr>
                        <tr>
                            <td>Reserve</td>
                            <td>{{ $registration['first'] }}</td>
                            <td>{{ $upgrade['first'] }}</td>
                            <td>{{ $personal['first'] }}</td>
                            <td>{{ $maintenance['first'] }}</td>
                            <td>
                                {{ $registration['first'] + $upgrade['first'] + $personal['first'] + $maintenance['first'] }}
                            </td>
                        </tr>
                        <tr>
                            <td>Leadership Bonus</td>
                            <td>{{ $registration['second'] }}</td>
                            <td>{{ $upgrade['second'] }}</td>
                            <td>{{ $personal['second'] }}</td>
                            <td>{{ $maintenance['second'] }}</td>
                            <td>
                                {{ $registration['second'] + $upgrade['second'] + $personal['second'] + $maintenance['second'] }}
                            </td>
                        </tr>
                        <tr>
                            <td>Pool bonus</td>
                            <td>{{ $registration['second'] }}</td>
                            <td>{{ $upgrade['second'] }}</td>
                            <td>{{ $personal['second'] }}</td>
                            <td>{{ $maintenance['second'] }}</td>
                            <td>
                                {{ $registration['second'] + $upgrade['second'] + $personal['second'] + $maintenance['second'] }}
                            </td>
                        </tr>
                        <tr>
                            <td>Head office stockist</td>
                            <td>{{ $registration['second'] }}</td>
                            <td>{{ $upgrade['second'] }}</td>
                            <td>{{ $personal['second'] }}</td>
                            <td>{{ $maintenance['second'] }}</td>
                            <td>
                                {{ $registration['second'] + $upgrade['second'] + $personal['second'] + $maintenance['second'] }}
                            </td>
                        </tr>
                        <tr>
                            <td>NGO</td>
                            <td>{{ $registration['second'] }}</td>
                            <td>{{ $upgrade['second'] }}</td>
                            <td>{{ $personal['second'] }}</td>
                            <td>{{ $maintenance['second'] }}</td>
                            <td>
                                {{ $registration['second'] + $upgrade['second'] + $personal['second'] + $maintenance['second'] }}
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Total: ${{ $totalQuantity }}</td>
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
