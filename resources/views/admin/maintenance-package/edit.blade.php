<x-layout.admin>
    <x-slot name="title">Maintenance Packages</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-2 mb-md-0">{{ __("edit_maintenance_package") }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin/maint-packages">{{ __("maintenance_packages") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("edit_package") }}</li>
            </ol>
          </nav>
    </div>

    @php
        $header = __("maintenance_packages");
    @endphp

    <x-go-back path="/{{ App::currentLocale() }}/admin/maint-packages" :title="$header" />

    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12 col-md-6 col-xl-5 col-xxl-4">
                <div class="border bg-white rounded shadow-sm p-3">
                    <h5 class="mb-3">{{ __("edit_package") }}</h5>

                    <ul>
                        @foreach($errors->all() as $message)
                            <li class="text-danger">{{ $message }}</li>
                        @endforeach
                    </ul>

                    <form action="/{{ App::getLocale() }}/admin/maint-packages/{{ $package->id }}" method="POST">
                        @csrf
                        @method("PUT")

                        <div class="form-group mb-3">
                            <label for="duration_in_months">{{ __("period") }} ({{ __("in_months") }})</label>
                            <input type="number" name="duration_in_months" id="duration_in_months" class="form-control" value="{{ $package->duration_in_months }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="total_products">{{ __("product_number") }}</label>
                            <input type="text" name="total_products" id="total_products" class="form-control" value="{{ $package->total_products }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="total_price">{{ __("price") }}</label>
                            <input type="text" name="total_price" id="total_price" class="form-control" value="{{ $package->total_price }}">
                        </div>
                        <div class="form-group mb-4">
                            <label for="bv_point">BV Points</label>
                            <input type="text" name="bv_point" id="bv_point" class="form-control" value="{{ $package->bv_point }}">
                        </div>

                        <button class="btn btn-success" type="submit">
                            <i class="bi bi-save"></i> {{ __("save") }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push("scripts")
        <script src="{{ asset("assets/js/admin/maintenance/edit.js") }}"></script>
    @endpush
</x-layout.admin>
