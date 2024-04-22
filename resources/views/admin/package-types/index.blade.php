<x-layout.admin>
    <x-slot name="title">Registration Package Types</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-2 mb-md-0">{{ __("registration_package_type") }}</h4>

        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __("registration_package_type") }}</li>
            </ol>
        </nav>
    </div>

    @php
        $header = __("available")
    @endphp

    <div class="container-fluid p-0 mb-3">
        <div class="row">
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                <x-model-summary :title="$header" icon="list-ol" :number="$totalTypes" class="bg-main" />
            </div>
        </div>
    </div>

    <div class="card border shadow-sm">
        <div class="card-header bg-white d-block d-md-flex align-items-center justify-content-between p-3">
            <h5 class="mb-2 mb-md-0">{{ __("available_types") }}</h5>

            <a href="/{{ App::currentLocale() }}/admin/package-types/create" class="btn btn-link">
                {{ __("add_package_type") }} <i class="bi bi-arrow-right-short"></i>
            </a>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover display" id="package-type-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>{{ __("package") }} {{ __("type") }}</th>
                            <th>{{ __("total_products") }}</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($package_types as $package)
                            <tr>
                                <td>{{ $package->id }}</td>
                                <td>
                                    <img src="{{ asset(str_replace("public", "storage", $package->image)) }}" alt="Product Image" class="table-img">
                                </td>
                                <td>{{ $package->registration_package->name }} {{ $package->type }}</td>
                                <td>
                                    @php
                                        $total = 0;
                                        foreach($package->products as $product):
                                            $total += $product->pivot->quantity;
                                        endforeach;
                                        echo $total;
                                    @endphp
                                </td>
                                <td>
                                    <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="{{ __("add_product") }}">
                                        <button onclick="setFormAction('/{{App::currentLocale()}}/admin/package-types/{{ $package->id }}/product')" class="action-btn text-success rounded"
                                            data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            <i class="bi bi-capsule"></i>
                                        </button>
                                    </span>
                                    <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="{{ __("edit") }}">
                                        <a href="/{{App::currentLocale()}}/admin/package-types/{{ $package->id }}/edit" class="action-btn text-secondary rounded">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    </span>
                                </td>
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Product</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="form">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="product">Product</label>
                            <select name="product_id" id="product_id" class="form-select">
                                <option value="">{{ __("select_product") }}</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label for="quantity">{{ __("quantity") }}</label>
                            <input type="number" name="quantity" id="quantity" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-success" type="submit">
                            <i class="bi bi-save"></i> {{ __("save") }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push("scripts")
        <script>
            $(document).ready(function() {
                $("#package-type-table").DataTable();
            });
        </script>
    @endpush
</x-layout.admin>
