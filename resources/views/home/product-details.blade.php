<x-layout.home>
    <x-slot name="title">Products</x-slot>

    <section class="bg-main dfl">
        <div class="container">
            <h1 class="text-white mb-3 mb-xl-4">{{ __("product_details") }}</h1>
            <p class="description text-light">{{ $product->name }}</p>
        </div>
    </section>

    <section class="pt-4">
        <div class="container">
            @php $goBackHeading = __("products"); @endphp
            <x-go-back path="/{{ App::currentLocale() }}/products" :title="$goBackHeading"/>
        </div>
    </section>

    <section class="dfl">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 col-md-4 col-xl-5 col-xxl-4">
                    <div class="overflow-hidden rounded shadow border">
                        <img src="{{ asset(str_replace("public", "storage", $product->image)) }}" class="w-100">
                    </div>
                </div>
                <div class="col-12 col-md-8 col-xl-6">
                    <div class="py-4 p-md-0">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="m-0">{{ $product->name }}</h4>
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

                    <div class="bg-white">
                        <h5 class="mb-3 mt-0">{{ __("product_description") }}</h5>
                        <input type="hidden" value="{{ $product->description }}" id="desc_product">
                        <div class="d-none">
                            <div id="editor"></div>
                        </div>
                        <p class="text-secondary" id="desc_prod"></p>
                    </div>
                </div>
            </div>
    </section>

    @push("scripts")
        <script>
            let description = new Quill("#editor", { theme: "snow" });
            let delta = JSON.parse(document.querySelector("#desc_product").value);
            description.setContents(delta);

            document.querySelector("#desc_prod").textContent = description.getText();
        </script>
    @endpush
</x-layout.home>
