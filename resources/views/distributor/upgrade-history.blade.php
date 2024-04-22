<x-layout.distributor>
    <x-slot name="title">Upgrade History</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-2 mb-md-0">{{ __("upgrade_history") }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("upgrade_history") }}</li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid p-0 mb-3">
        <div class="row">
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $firstHeading = __("available"); @endphp
                <x-model-summary :title="$firstHeading" icon="graph-up-arrow" :number="$total" class="bg-main" />
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
                            <th>{{ __("previous_package") }}</th>
                            <th>{{ __("current_package") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($upgrades as $upgrade)
                            <tr>
                                <td>{{ $upgrade->created_at }}</td>
                                <td>{{ $upgrade->upgradeType->current_package->name }}</td>
                                <td>{{ $upgrade->registrationPackage->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    @push("scripts")
        <script src="{{ asset("assets/js/distributor/reset-maintenance-store.js") }}"></script>
    @endpush
</x-layout.distributor>
