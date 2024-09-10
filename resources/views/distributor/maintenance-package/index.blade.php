<x-layout.distributor>
    <x-slot name="title">Maintenance Packages</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-2 mb-md-0">
            {{ __("maintenance_packages") }}
        </h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("maintenance_packages") }}</li>
            </ol>
          </nav>
    </div>

    <div class="container-fluid p-0 mb-3">
        <div class="row">
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $firstHeading = __("status") @endphp
                <x-model-summary :title="$firstHeading" icon="gear" :number="$status" class="bg-main" />
            </div>
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $secondHeading = __("remaining_days") @endphp
                <x-model-summary :title="$secondHeading" icon="clock-history" :number="$remainingDays" class="bg-tertiary" />
            </div>
        </div>
    </div>

    <div class="card border shadow-sm">
        <div class="card-header bg-white d-block d-md-flex align-items-center justify-content-between p-3">
            <h5 class="mb-2 mb-md-0">{{ __("available_packages") }}</h5>
            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="bi bi-check2-square"></i> {{ __("choose_package") }}
            </button>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover display" id="product-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __("duration_in_months") }}</th>
                            <th>{{ __("total_products") }}</th>
                            {{-- <th>{{ __("price") }}</th>
                            <th>Point</th> --}}
                            {{-- <th>Actions</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($packages as $package)
                            <tr>
                                <td>{{ $package->id }}</td>
                                <td>{{ $package->duration_in_months }}</td>
                                <td>{{ $package->total_products }}</td>
                                {{-- <td>${{ $package->total_price }}</td>
                                <td>{{ $package->bv_point }}</td> --}}
                                {{-- <td>
                                    <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="Edit">
                                        <a href="/{{ App::currentLocale() }}/admin/maint-packages/{{ $package->id }}/edit" class="action-btn text-secondary rounded">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    </span>
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __("select_package_type") }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/{{ App::currentLocale() }}/distributor/maint-packages" method="post">
                    @csrf

                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label for="package_id">{{ __("select_package_type") }}</label>
                            <select name="package_id" id="package_id" class="form-select">
                                <option value="">{{ __("select_preferred_package") }}</option>
                                @foreach ($packages as $package)
                                    <option value="{{ $package->id }}">{{ $package->duration_in_months }} {{ __("month") }}</option>
                                @endforeach
                            </select>
                        </div>
                        <p>
                            <i class="bi bi-info-circle"></i> {{ __("maintenance_selection_description") }}
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{ __("close") }}
                        </button>
                        <button type="submit" class="btn btn-success">
                            {{ __("continue") }} <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push("scripts")
        <script>
            $(document).ready(function() {
                $("#product-table").DataTable();
            });
        </script>
        <script src="{{ asset("assets/js/distributor/reset-maintenance-store.js") }}"></script>
    @endpush
</x-layout.distributor>
