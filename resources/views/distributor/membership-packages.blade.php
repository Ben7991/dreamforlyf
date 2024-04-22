<x-layout.distributor>
    <x-slot name="title">Membership Packages</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-2 mb-md-0">
            {{ __("membership_package") }}
        </h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("membership_package") }}</li>
            </ol>
        </nav>
    </div>

    @php
        $currentPackage = Auth::user()->distributor->registrationPackage
    @endphp

    <div class="container-fluid p-0 mb-3">
        <div class="row">
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $heading = __("current_package"); @endphp
                <x-model-summary :title="$heading" icon="list-ol" :number="$currentPackage->name" class="bg-main" />
            </div>
        </div>
    </div>

    <div class="card border shadow-sm">
        <div class="card-header bg-white p-3 d-block d-md-flex align-items-center justify-content-between">
            <h5 class="mb-2 mb-md-0">{{ __("available_packages") }}</h5>
            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="bi bi-check2-square"></i> {{ __("upgrade") }}
            </button>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover display bg-container" id="package-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __("name") }}</th>
                            <th>{{ __("price") }}</th>
                            <th>Bv Point</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($packages as $package)
                            <tr>
                                <td>{{ $package->id }}</td>
                                <td>{{ $package->name }}</td>
                                <td>${{ number_format($package->price, 2) }}</td>
                                <td>{{ $package->bv_point }}</td>
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __("choose_package") }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/{{ App::currentLocale() }}/distributor/membership-packages/upgrade" method="POST">
                    @csrf

                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="package">{{ __("select_preferred_package") }}</label>
                            <select name="package" id="package" class="form-select">
                                <option value="">{{ __("select_preferred_package") }}</option>
                                @foreach ($upgradePackages as $package)
                                    <option value="{{ $package->id }}">{{ $package->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <p>When upgrading from your current package to another, the following with take place</p>
                        <ul>
                            <li>A cash difference between the new package and your current package with de deducted</li>
                            <li>You select your products according to the new package and process ends with bonus and bv points shared to uplines</li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("close") }}</button>
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
                $("#package-table").DataTable();
            });
        </script>
        <script src="{{ asset("assets/js/distributor/reset-maintenance-store.js") }}"></script>
    @endpush
</x-layout.distributor>
