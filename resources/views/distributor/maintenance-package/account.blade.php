<x-layout.distributor>
    <x-slot name="title">Maintenance Packages</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3 mb-xxl-4">
        <h4 class="mb-2 mb-md-0">
            {{ __("select_products") }} ({{ $package->total_products }})
        </h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("maintenance_packages") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("select_products") }}</li>
            </ol>
          </nav>
    </div>

    @php $goBackHeading = __("maintenance_packages"); @endphp
    <x-go-back path="/{{ App::currentLocale() }}/distributor/maint-packages" :title="$goBackHeading"/>
    <input type="hidden" id="totalQuantity" value="{{ $package->total_products }}">
    <input type="hidden" id="packageId" value={{ $package->id }}>
    <input type="hidden" id="token" value="{{ csrf_token() }}">
    <input type="hidden" id="locale" value="{{ App::currentLocale() }}">

    <div class="container-fluid p-0">
        <div class="d-flex align-items-center justify-content-between mb-3 mb-xxl-4 px-1">
            <div>
                <div class="spinner-border text-primary d-none" role="status" id="spinner">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <button type="button" class="btn btn-primary position-relative" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartCanvas" aria-controls="cartCanvas">
                <i class="bi bi-cart4"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-count">
                  0
                  <span class="visually-hidden">unread messages</span>
                </span>
              </button>
        </div>
        <div class="row">
            @foreach($products as $product)
                <div class="col-12 col-md-6 col-xl-4 col-xxl-3 mb-3">
                    <div class="d-flex flex-column rounded overflow-hidden bg-white">
                        <img src="{{ asset(str_replace("public", "storage", $product->image)) }}" alt="" class="w-100">
                        <div class="py-4 px-3 border-top">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <p class="m-0 text-secondary">{{ $product->name }}</p>
                                <span class="px-4 rounded-pill d-inline-block text-white {{ $product->status === 'in-stock' ? 'bg-success' : 'bg-danger' }}">
                                    @if ($product->status === "in-stock")
                                        {{ __("in_stock") }}
                                    @else
                                        {{ __("out_of_stock") }}
                                    @endif
                                </span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="m-0">${{ $product->price }}</h5>
                                <button class="btn btn-secondary btn-cart" onclick="addCartItem('{{ $product->id }}')">
                                    <i class="bi bi-cart-plus"></i> {{ __("add_to_cart") }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartCanvas" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">{{ __("cart_items") }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body overflow-y-auto">
            <div class="text-center" id="no-item-description">
                <i class="bi bi-basket fs-1 text-secondary"></i>
                <p class="text-secondary">{{ __("no_item_description") }}</p>
            </div>
            <div id="cart-details" class="d-none">
                <div id="cart-items w-100" id="cart-items-holder">
                    {{-- <div class="cart-item mb-3 pb-2 border-bottom">
                        <img src="{{ asset("assets/img/PCOS.jpg") }}" class="cart-item-img">
                        <p class="m-0 text-secondary">D LYF Diab Care</p>
                        <div class="cart-item-action">
                            <button class="action-btn text-secondary">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="text" class="cart-item-input text-center">
                            <button class="action-btn text-secondary">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                        <button class="btn btn-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div> --}}
                </div>
                <div class="d-flex align-items-center justify-content-center gap-3 mb-4">
                    <p class="m-0 text-secondary">{{ __("total_quantities") }}</p> <span class="text-secondary">=</span> <p id="total-quantity" class="m-0">0</p>
                </div>
                <div class="text-center">
                    <button class="btn btn-success" onclick="completeOrder()" data-bs-dismiss="offcanvas" aria-label="Close" type="button">
                        <i class="bi bi-cart-check"></i> {{ __("complete_orders") }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push("scripts")
       <script src="{{ asset("assets/js/distributor/account.js") }}"></script>
    @endpush
</x-layout.distributor>
