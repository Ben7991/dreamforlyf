<x-layout.distributor>
    <x-slot name="title">Package Types</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-2 mb-md-0">{{ __("package") }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/distributor">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Products</li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid p-0 mb-4">
        <div class="row mb-4">
            @foreach ($packages as $package)
                <div class="col-12 col-md-4 col-xl-3 mb-3">
                    <a href="/{{ App::currentLocale() }}/distributor/package-types/{{ $package->id }}/show" class="link-offset-2 link-underline link-underline-opacity-0">
                        <div class="d-flex flex-column rounded overflow-hidden bg-white">
                            <img src="{{ asset(str_replace("public", "storage", $package->image)) }}" alt="" class="w-100">
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        {{ $packages->links() }}
    </div>

    @push("scripts")
        <script src="{{ asset("assets/js/distributor/reset-maintenance-store.js") }}"></script>
    @endpush
</x-layout.distributor>
