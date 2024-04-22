<x-layout.admin>
    <x-slot name="title">Products</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-2 mb-md-0">{{ __("products") }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item" aria-current="page">{{ __("products") }}</li>
            </ol>
          </nav>
    </div>

    @php
        $availHeader = __("available");
        $inStockHeader = __("in_stock");
        $outStockHeader = __("out_of_stock")
    @endphp

    <div class="container-fluid p-0 mb-3">
        <div class="row">
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                <x-model-summary :title="$availHeader" icon="list-ol" :number="$total" class="bg-main" />
            </div>
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                <x-model-summary :title="$inStockHeader" icon="list-check" :number="$in_stock" class="bg-tertiary" />
            </div>
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                <x-model-summary :title="$outStockHeader" icon="exclamation-circle" :number="$out_stock" class="bg-other" />
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white d-block d-md-flex align-items-center justify-content-between p-3">
            <h5 class="mb-2 mb-md-0">{{ __("available_products")}}</h5>

            <a href="/{{ App::currentLocale() }}/admin/products/create" class="btn btn-link">
                {{ __("add") }} {{ __("product") }} <i class="bi bi-arrow-right-short"></i>
            </a>
        </div>
        <div class="card-body p-3 text-secondary">
            <div class="table-responsive">
                <table class="table table-hover display" id="product-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>{{ __("name") }}</th>
                            <th>{{ __("quantity") }}</th>
                            <th>{{ __("price") }}</th>
                            <th>{{ __("status") }}</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    <img src="{{ asset(str_replace("public", "storage", $product->image)) }}" alt="Product Image" class="table-img">
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->quantity }}</td>
                                <td>${{ $product->price }}</td>
                                <td>
                                    {{ $product->status === "in-stock" ? __("in_stock") : __("out_of_stock") }}
                                </td>
                                <td>
                                    <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="{{ __("edit") }}">
                                        <a href="/{{ App::getLocale() }}/admin/products/{{ $product->id }}/edit" class="action-btn text-secondary rounded">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    </span>

                                    <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="{{ __("mark_status") }}">
                                        <button class="action-btn text-primary rounded" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                            onclick="setFormAction('/{{App::getLocale()}}/admin/products/{{ $product->id }}/stock-status')">
                                            @if($product->status === "in-stock")
                                                <i class="bi bi-eye-slash text-primary"></i>
                                            @else
                                                <i class="bi bi-check2 text-success"></i>
                                            @endif
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

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __("stock_availability") }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="form">
                    @csrf
                    @method("PUT")
                    <div class="modal-body">
                        <p class="m-0">{{ __("stock_availability_des") }} {{ __("product") }}?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("close") }}</button>
                        <button type="submit" class="btn btn-main">{{ __("save") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push("scripts")
        <script>
            $(document).ready(function() {
                $("#product-table").DataTable();
            });
        </script>
    @endpush
</x-layout.admin>
