<x-layout.admin>
    <x-slot name="title">Registration Package Types</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-2 mb-md-0">{{ __("registration_package_type") }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin/packages-types">{{ __("registration_package_type") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("edit_type") }}</li>
            </ol>
          </nav>
    </div>

    @php
        $header = __("registration_package_type")
    @endphp

    <x-go-back path="/{{ App::currentLocale() }}/admin/package-types" :title="$header" />

    <div class="container-fluid p-0 mb-4">
        <div class="row">
            <div class="col-12 col-md-5 col-xl-4 col-xxl-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="m-0">{{ __("upload_image") }}</h5>
                    <button class="btn btn-primary" id="upload-btn">
                        <i class="bi bi-cloud-arrow-up"></i> {{ __("upload") }}
                    </button>
                </div>
                <div class="upload-img rounded overflow-hidden m-1 m-md-0">
                    <div class="upload-notice p-2 d-none">
                        <p class="m-0 text-center">{{ __("upload_description") }}</p>
                    </div>
                    <img src="{{ asset(str_replace("public", "storage", $type->image)) }}" alt="Uploaded product image" class="uploaded-image">
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-5 col-xxl-4">
                <div class="border bg-white rounded shadow-sm p-3">
                    <h5 class="mb-3">{{ __("edit_type") }}</h5>

                    <ul>
                        @foreach($errors->all() as $message)
                            <li class="text-danger">{{ $message }}</li>
                        @endforeach
                    </ul>

                    <form action="/{{ App::getLocale() }}/admin/package-types/{{ $type->id }}" method="POST" id="type-form"
                        enctype="multipart/form-data">
                        @csrf
                        @method("PUT")

                        <div class="form-group mb-3">
                            <label for="type">{{ __("type") }}</label>
                            <input type="text" name="type" id="type" class="form-control" value="{{ $type->type }}">
                            <small class="d-none text-danger"></small>
                        </div>
                        <input type="file" name="file" id="file-upload" class="d-none" accept="image/jpeg, image/jpg, image/png">
                        <div class="form-group mb-4">
                            <label for="package_id">{{ __("registration_package_type") }}</label>
                            @if(count($registration_packages))
                                <select name="package_id" id="package_id" class="form-select">
                                    <option value="">{{ __("select_preferred_package") }}</option>
                                    @foreach($registration_packages as $package)
                                        <option value="{{ $package->id }}" {{ $type->package_id === $package->id ? "selected" : ""}}>{{ $package->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <p class="m-0">{{ __("no_registration_package_message") }}</p>
                            @endif
                            <small class="d-none text-danger"></small>
                        </div>

                        <button class="btn btn-success" type="submit">
                            <i class="bi bi-save"></i> {{ __("save") }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card border shadow-sm mb-3">
        <div class="card-header bg-white p-3">
            <h5 class="m-0">{{ __("available_products") }}</h5>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover display" id="package-type-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __("name") }}</th>
                            <th>{{ __("quantity") }}</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($type->products as $product)
                            <tr>
                                <td>{{ $product->pivot->id }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->pivot->quantity }}</td>
                                <td>
                                    <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="{{ __("view") }}">
                                        <input type="hidden" value="{{ $product->pivot->product_id }}">
                                        <button class="action-btn text-secondary edit-btn" onclick="setFormAction('/{{ App::getLocale() }}/admin/package-types/{{ $product->pivot->id }}/product')"
                                            data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <input type="hidden" value="{{ $product->pivot->quantity }}">
                                    </span>
                                    <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="Remove">
                                        <button class="action-btn text-danger" onclick="setDeleteFormAction('/{{ App::getLocale() }}/admin/package-types/{{ $product->pivot->id }}/product')"
                                            data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Product Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="form">
                    @csrf
                    @method("PUT")

                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="product_id">{{ __("product") }}</label>
                            @if(count($products) > 0)
                                <select name="product_id" id="product_id" class="form-select">
                                    <option value="">{{ __("select_product") }}</option>
                                    @foreach($products as $prod)
                                        <option value="{{ $prod->id }}" {{ $type }}>{{ $prod->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <p class="m-0">{{ __("no_product_message") }}</p>
                            @endif
                        </div>
                        <div class="form-group mb-4">
                            <label for="quantity">{{ __("quantity") }}</label>
                            <input type="number" class="form-control" id="quantity" name="quantity">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("close") }}</button>
                        <button type="submit" class="btn btn-main">{{ __("save") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteModalLabel">Remove Product</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="delete-form">
                    @csrf
                    @method("DELETE")

                    <div class="modal-body">
                        <p>Are you sure you want to remove the product from this package?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("close") }}</button>
                        <button type="submit" class="btn btn-danger">Remove</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push("scripts")
        <script src="{{ asset("assets/js/admin/package-type/edit.js") }}"></script>
        <script>
            function setDeleteFormAction(action) {
                let form = document.querySelector("#delete-form");
                form.action = action;
            }
        </script>
    @endpush
</x-layout.admin>
