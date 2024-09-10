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
            <div class="spinner-border d-none" role="status" id="spinner">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <div class="card border shadow-sm">
        <div class="card-header bg-white p-3">
            <h5 class="m-0">Overall History</h5>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover display" id="product-table">
                    <thead>
                        <tr>
                            <th>{{ __("name") }}</th>
                            <th>Total Distributors</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($result as $data)
                            <tr>
                                <td>{{ $data->name }}</td>
                                <td>{{ $data->total_number }}</td>
                            </tr>
                        @endforeach
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
                        startDate = start.format("YYYY-MM-DD") + " 00:00:00";
                        endDate = end.format("YYYY-MM-DD") + " 00:00:00";
                    }
                );
                datePicker.on("apply.daterangepicker", function(ev, picker){
                    spinner.removeClass("d-none");

                    $.ajax({
                        url: `/admin/analytics-data?q=registration&start=${startDate}&end=${endDate}`,
                        method: "GET",
                        headers: {
                            "X-CSRF-TOKEN": token.value
                        },
                        success: function(data, status, xhr) {
                            for(let row of table.children[1].children) {
                                row.remove();
                            }

                            createRow(table.children[1], data.data);
                        },
                        error: function(xhr, status, error) {
                            alert("Something went wrong, please contact developer")
                        },
                        complete: function(xhr, status) {
                            spinner.addClass("d-none");
                        }
                    });
                });

                function createRow(parent, data) {
                    for (let row of data) {
                        let tableRow = document.createElement("tr");

                        let firstColumn = document.createElement("td");
                        firstColumn.textContent = row.name;

                        let secondColumn = document.createElement("td");
                        secondColumn.textContent = row.total_number;

                        tableRow.appendChild(firstColumn);
                        tableRow.appendChild(secondColumn);
                        parent.appendChild(tableRow);
                    }
                }
            });
        </script>
    @endpush
</x-layout.admin>
