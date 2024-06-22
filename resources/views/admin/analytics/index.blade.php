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

    <ul class="nav nav-pills mb-3 mb-xxl-4">
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="/{{ App::getLocale() }}/admin/analytics">Orders</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/{{ App::getLocale() }}/admin/analytics/registration">Registration</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/{{ App::getLocale() }}/admin/analytics/bonus">Bonus</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/{{ App::getLocale() }}/admin/analytics/withdrawal">Withdrawal</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/{{ App::getLocale() }}/admin/analytics/maintenance">Maintenance</a>
        </li>
    </ul>

    <div class="d-flex justify-content-end">
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


    @push("scripts")
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <script>
            $(document).ready(function(){
                const spinner = $("#spinner");
                const datePicker = $("#datePicker");
                const token = document.querySelector("#token");

                datePicker.daterangepicker();
                datePicker.on("apply.daterangepicker", function(ev, picker){
                    spinner.removeClass("d-none");

                    $.ajax({
                        url: "/admin/analytics-data?q=order",
                        method: "GET",
                        headers: {
                            "X-CSRF-TOKEN": token.value
                        },
                        success: function(data, status, xhr) {
                            console.log(data);
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr);
                        },
                        complete: function(xhr, status) {
                            spinner.addClass("d-none");
                        }
                    });
                });
            });
        </script>
    @endpush
</x-layout.admin>
