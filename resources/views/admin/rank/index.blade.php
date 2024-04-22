<x-layout.admin>
    <x-slot name="title">Ranks</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-2 mb-md-0">{{ __("ranks") }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("ranks") }}</li>
            </ol>
          </nav>
    </div>

    @php
        $header = __("available")
    @endphp

    <div class="container-fluid p-0 mb-3">
        <div class="row">
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                <x-model-summary :title="$header" icon="list-ol" :number="$total" class="bg-main" />
            </div>
        </div>
    </div>

    <div class="card border shadow-sm">
        <div class="card-header bg-white d-block d-md-flex align-items-center justify-content-between p-3">
            <h5 class="mb-2 mb-md-0">{{ __("available") }} {{ __("ranks") }}</h5>

            <a href="/{{ App::currentLocale() }}/admin/ranks/create" class="btn btn-link">
                {{ __("add") }} {{ __("ranks") }} <i class="bi bi-arrow-right-short"></i>
            </a>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover display" id="product-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __("name") }}</th>
                            <th>BV Points</th>
                            <th>{{ __("award") }}</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ranks as $rank)
                            <tr>
                                <td>{{ $rank->id }}</td>
                                <td>{{ $rank->name }}</td>
                                <td>{{ number_format($rank->bv_point) }}</td>
                                <td>{{ $rank->award }}</td>
                                <td>
                                    <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="{{ __("edit") }}">
                                        <a href="/{{ App::getLocale() }}/admin/ranks/{{ $rank->id }}/edit" class="action-btn text-secondary rounded">
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

    @push("scripts")
        <script>
            $(document).ready(function() {
                $("#product-table").DataTable();
            });
        </script>
    @endpush
</x-layout.admin>
