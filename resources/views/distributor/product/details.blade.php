<x-layout.distributor>
    <x-slot name="title">Products</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-2 mb-md-0">{{ $product->name }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/distributor">{{ __("home") }}</a></li>
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/distributor/products">{{ __("products") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("product_details") }}</li>
            </ol>
        </nav>
    </div>

    @php $goBackHeading = __("products"); @endphp
    <x-go-back path="/{{ App::currentLocale() }}/distributor/products" :title="$goBackHeading"/>

    <div class="container-fluid p-0 mb-4">
        <div class="row mb-4">
            <div class="col-12 col-md-4 col-xl-5 col-xxl-4">
                <div class="overflow-hidden rounded shadow-sm">
                    <img src="{{ asset(str_replace("public", "storage", $product->image)) }}" class="w-100">
                </div>
            </div>
            <div class="col-12 col-md-8 col-xl-6 col-xxl-5">
                <div class="py-4 p-md-0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="m-0 fw-normal">{{ $product->name }}</h4>
                        <span class="px-4 rounded-pill d-inline-block text-white {{ $product->status === 'in-stock' ? 'bg-success' : 'bg-danger' }}">
                            @if ($product->status === "in-stock")
                                {{ __("in_stock") }}
                            @else
                            {{ __("out_of_stock") }}
                            @endif
                        </span>
                    </div>
                    <h1 class="text-main m-0">${{ $product->price }}</h1>
                </div>

                <hr class="my-3">

                <div class="p-3 p-xxl-4 rounded bg-white">
                    <h5 class="mb-3 mt-0 fw-normal">{{ __("purchase_product") }}</h5>

                    <form action="/{{ App::currentLocale() }}/distributor/products/{{ $product->id }}/purchase" method="POST"
                        id="form">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="quantity">{{ __("quantity") }}</label>
                            <input type="number" name="quantity" id="quantity" class="form-control">
                            <small class="text-danger d-none"></small>
                        </div>
                        <div class="form-group mb-4">
                            <label for="stockist">{{ __("select_stockist") }}</label>
                            <select name="stockist" id="stockist" class="form-select">
                                <option value="">{{ __("select_stockist") }}</option>
                                @foreach ($stockists as $stockist)
                                    <option value="{{ $stockist->id }}">{{ $stockist->code }}</option>
                                @endforeach
                            </select>
                            <small class="text-danger d-none"></small>
                        </div>
                        <button type="submit" class="btn btn-success btn-submit">
                            <span class="main-btn">
                                <i class="bi bi-cart4"></i>&nbsp;{{ __("purchase_now") }}
                            </span>
                            <span class="loader d-none">
                                <div class="d-flex align-items-center gap-1">
                                    <div class="spinner-border spinner-border-sm" role="status" id="loader">
                                        <span class="visually-hidden">{{ __("loading") }}</span>
                                    </div>
                                    {{ __("loading") }}
                                </div>
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h5>{{ __("product_description") }}</h5>
                <p>{{ $product->description }}</p>
            </div>
        </div>
    </div>

    @push("scripts")
        <script src="{{ asset("assets/js/distributor/reset-maintenance-store.js") }}"></script>
        <script src="{{ asset("assets/js/distributor/product.js") }}"></script>
    @endpush
</x-layout.distributor>
