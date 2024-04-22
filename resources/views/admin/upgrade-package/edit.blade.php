<x-layout.admin>
    <x-slot name="title">Upgrade Packages</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-2 mb-md-0">{{ __("upgrade_packages") }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin/upgrade-packages">{{ __("upgrade_packages") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("edit_type") }}</li>
            </ol>
          </nav>
    </div>

    @php
        $header = __("upgrade_packages")
    @endphp

    <x-go-back path="/{{ App::currentLocale() }}/admin/upgrade-packages" :title="$header" />

    <div class="container-fluid p-0 mb-3 mb-xxl-4">
        <div class="row">
            <div class="col-12 col-md-5 col-xl-4 col-xxl-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="m-0">{{ __("upload_image") }}</h5>
                    <button class="btn btn-primary" id="upload-btn">
                        <i class="bi bi-cloud-arrow-up"></i> {{ __("upload") }}
                    </button>
                </div>
                <div class="upload-img rounded overflow-hidden m-1 m-md-0">
                    <div class="upload-notice p-2 {{ $package->image === null ? "" : "d-none" }}">
                        <p class="m-0 text-center">{{ __("upload_description") }}</p>
                    </div>
                    <img src="{{ asset(str_replace("public", "storage", $package->image)) }}" alt="Uploaded product image"
                        class="uploaded-image {{ $package->image === null ? "d-none" : "" }}">
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

                    <form action="/{{ App::getLocale() }}/admin/upgrade-packages/{{ $package->id }}" method="POST" id="package-form"
                        enctype="multipart/form-data">
                        @csrf
                        @method("PUT")

                        <div class="form-group mb-3">
                            <label for="type">{{ __("type") }}</label>
                            <input type="text" name="type" id="type" class="form-control" value="<?php echo $package->type ?>">
                            <small class="d-none text-danger"></small>
                        </div>
                        <input type="file" name="image" id="file-upload" class="d-none" accept="image/jpeg, image/jpg, image/png">
                        <div class="form-group mb-4">
                            <label for="current_package">{{ __("current_package") }}</label>
                            @if(count($registration_packages))
                                <select name="current_package" id="current_package" class="form-select">
                                    <option value="">{{ __("select_preferred_package") }}</option>
                                    @foreach($registration_packages as $currentPackage)
                                        <option value="{{ $currentPackage->id }}" {{ $currentPackage->id === $package->current_package_id ? "selected" : ""}}>
                                            {{ $currentPackage->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <p class="m-0">{{ __("no_registration_package_message") }}</p>
                            @endif
                            <small class="d-none text-danger"></small>
                        </div>
                        <div class="form-group mb-4">
                            <label for="next_package">{{ __("next_package") }}</label>
                            @if(count($registration_packages))
                                <select name="next_package" id="next_package" class="form-select">
                                    <option value="">{{ __("select_preferred_package") }}</option>
                                    @foreach($registration_packages as $currentPackage)
                                        <option value="{{ $currentPackage->id }}" {{ $currentPackage->id === $package->next_package_id ? "selected" : ""}}>
                                            {{ $currentPackage->name }}
                                        </option>
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
                        @foreach ($package->products as $product)
                            <tr>
                                <td>{{ $product->pivot->id }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->pivot->quantity }}</td>
                                <td>
                                    <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="{{ __("view") }}">
                                        <input type="hidden" value="{{ $product->pivot->product_id }}">
                                        <button class="action-btn text-secondary" onclick="setFormAction('/{{ App::getLocale() }}/admin/upgrade-packages/{{ $product->pivot->id }}/product')"
                                            data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <input type="hidden" value="{{ $product->pivot->quantity }}">
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
                                        <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-danger d-none"></small>
                            @else
                                <p class="m-0">{{ __("no_product_message") }}</p>
                            @endif
                        </div>
                        <div class="form-group mb-4">
                            <label for="quantity">{{ __("quantity") }}</label>
                            <input type="number" class="form-control" id="quantity" name="quantity">
                            <small class="text-danger d-none"></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("close") }}</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> {{ __("save") }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push("scripts")
        <script src="{{ asset("assets/js/admin/upgrade-package/edit.js") }}"></script>
    @endpush
</x-layout.admin>
