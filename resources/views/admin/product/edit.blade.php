<x-layout.admin>
    <x-slot name="title">Products</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-2 mb-md-0">{{ __("edit") }} {{ __("product") }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin/products">{{ __("products") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("add") }} {{ __("product") }}</li>
            </ol>
          </nav>
    </div>

    @php
        $header = __("products")
    @endphp

    <x-go-back path="/{{ App::currentLocale() }}/admin/products" :title="$header" />

    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12 col-md-4 col-xxl-3 mb-3 mb-md-0">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="m-0">{{ __("upload_image") }}</h5>
                    <button class="btn btn-primary" id="upload-btn">
                        <i class="bi bi-cloud-arrow-up"></i> {{ __("upload") }}
                    </button>
                </div>
                <div class="upload-img rounded overflow-hidden m-1 m-md-0">
                    <div class="upload-notice p-2">
                        <p class="m-0 text-center">{{ __("upload_description") }}</p>
                    </div>
                    <img src="{{ asset(str_replace("public", "storage", $product->image)) }}" alt="Uploaded product image" class="uploaded-image">
                    <input type="hidden" value="{{ $product->image }}">
                </div>
            </div>
            <div class="col-12 col-md-8 col-xxl-9">
                <div class="border bg-white rounded shadow-sm p-3">
                    <h5 class="mb-3">{{ __("product_details") }}</h5>

                    <ul>
                        @foreach($errors->all() as $message)
                            <li class="text-danger">{{ $message }}</li>
                        @endforeach
                    </ul>

                    <form action="/{{App::getLocale()}}/admin/products/{{$product->id}}" method="POST" enctype="multipart/form-data" id="form">
                        @csrf
                        @method("PUT")

                        <div class="form-group mb-3">
                            <label for="name">{{ __("name") }}</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $product->name }}">
                            <small class="text-danger d-none"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="quantity">{{ __("quantity") }}</label>
                            <input type="text" name="quantity" id="quantity" class="form-control" value="{{ $product->quantity }}">
                            <small class="text-danger d-none"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="price">{{ __("price") }}</label>
                            <input type="text" name="price" id="price" class="form-control" placeholder="0.00" value="{{ $product->price }}">
                            <small class="text-danger d-none"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="bv_point">Bv Point</label>
                            <input type="number" name="bv_point" id="bv_point" class="form-control" value="{{ $product->bv_point }}">
                            <small class="text-danger d-none"></small>
                        </div>
                        <input type="file" name="image" id="image" class="form-control d-none">
                        <div class="form-group mb-3">
                            <label for="editor_en">{{ __("enlish") }} Description</label>
                            <div id="editor_en"></div>
                            <input type="hidden" name="description_en" id="description_en" value="{{ $product->description_en }}">
                        </div>
                        <div class="form-group mb-4">
                            <label for="editor_fr">{{ __("french") }} Description</label>
                            <div id="editor_fr"></div>
                            <input type="hidden" name="description_fr" id="description_fr" value="{{ $product->description_fr }}">
                        </div>

                        <div class="form-group mb-4">
                            <label for="status">{{ __("status") }}</label>
                            <select name="status" id="status" class="form-select">
                                <option value="in-stock" {{ $product->status === "in-stock" ? "selected" : "" }}>In stock</option>
                                <option value="out-of-stock" {{ $product->status === "out-of-stock" ? "selected" : "" }}>Out of stock</option>
                            </select>
                        </div>
                        <button class="btn btn-success">
                            <i class="bi bi-save"></i> {{ __("save") }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push("scripts")
        <script src="{{ asset("assets/js/admin/product/edit.js") }}"></script>
    @endpush
</x-layout.admin>
