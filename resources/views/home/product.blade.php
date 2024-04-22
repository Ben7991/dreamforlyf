<x-layout.home>
    <x-slot name="title">Products</x-slot>

    <section class="bg-main dfl">
        <div class="container">
            <h1 class="text-white mb-3 mb-xl-4">{{ __("products") }}</h1>
            <p class="description text-light">{{ __("product_info") }}</p>
        </div>
    </section>

    <section class="dfl bg-light-subtle text-light-emphasis">
        <div class="container">
            <div class="row">
                @foreach ($products as $product)
                    <div class="col-12 col-md-4 col-xxl-3 mb-3">
                        <a href="/{{ App::currentLocale() }}/products/{{ $product->id }}/details" class="link-offset-2 link-underline link-underline-opacity-0">
                            <div class="d-flex flex-column rounded overflow-hidden bg-white shadow border">
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
                                    <h5 class="m-0">${{ $product->price }}</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            {{ $products->links() }}
        </div>
    </section>

</x-layout.home>
