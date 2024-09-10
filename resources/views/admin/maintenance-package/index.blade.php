<x-layout.admin>
    <x-slot name="title">Maintenance Packages</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-2 mb-md-0">{{ __("maintenance_packages") }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("maintenance_packages") }}</li>
            </ol>
          </nav>
    </div>

    @php
        $header = __("available");
    @endphp

    <div class="container-fluid p-0 mb-3">
        <div class="row">
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                <x-model-summary :title="$header" icon="list-ol" :number="$total" class="bg-main" />
            </div>
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                <x-model-summary :title="$header" icon="eye-slash" :number="$hidden" class="bg-tertiary" />
            </div>
        </div>
    </div>

    <div class="card border shadow-sm">
        <div class="card-header bg-white d-block d-md-flex align-items-center justify-content-between p-3">
            <h5 class="mb-2 mb-md-0">{{ __("available_packages") }}</h5>

            <a href="/{{ App::currentLocale() }}/admin/maint-packages/create" class="btn btn-link">
                {{ __("add_package") }} <i class="bi bi-arrow-right-short"></i>
            </a>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover display" id="product-table">
                    <thead>
                        <tr>
                            <th>{{ __("duration_in_months") }}</th>
                            <th>{{ __("total_products") }}</th>
                            {{-- <th>{{ __("price") }}</th>
                            <th>Point</th> --}}
                            <th>{{ __("status") }}</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($packages as $package)
                            <tr>
                                <td>{{ $package->duration_in_months }}</td>
                                <td>{{ $package->total_products }}</td>
                                {{-- <td>${{ $package->total_price }}</td>
                                <td>{{ $package->bv_point }}</td> --}}
                                <td>
                                    @if ($package->status === 'ACTIVE')
                                        <span class="badge text-bg-success">
                                            {{ $package->status }}
                                        </span>
                                    @else
                                        <span class="badge text-bg-danger">
                                            {{ $package->status }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="{{ __("edit") }}">
                                        <a href="/{{ App::currentLocale() }}/admin/maint-packages/{{ $package->id }}/edit" class="action-btn text-secondary rounded">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    </span>
                                    <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="{{ __("mark_status") }}">
                                        <button onclick="setFormAction('/{{ App::currentLocale() }}/admin/maint-packages/{{ $package->id }}')"
                                            class="action-btn text-@php echo $package->status === 'ACTIVE' ? 'danger' : 'success'; @endphp rounded"
                                            data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            @if ($package->status === 'ACTIVE')
                                                <i class="bi bi-eye-slash"></i>
                                            @else
                                                <i class="bi bi-eye"></i>
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
              <h1 class="modal-title fs-5" id="exampleModalLabel">Change package status</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="form">
                @csrf
                @method("DELETE")
                <div class="modal-body">
                    <p>Are you sure you want to change maintenance package status?</p>
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
