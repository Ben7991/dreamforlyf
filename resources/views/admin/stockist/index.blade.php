<x-layout.admin>
    <x-slot name="title">Stockists</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-2 mb-md-0">{{ __("stockists") }}</h4>

        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __("stockists") }}</li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid p-0 mb-3">
        <div class="row">
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $firstHeading = __("available"); @endphp
                <x-model-summary :title="$firstHeading" icon="list-ol" :number="$totalStockist" class="bg-main"/>
            </div>
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $secondHeading = __("suspended_users"); @endphp
                <x-model-summary :title="$secondHeading" icon="person-fill-slash" :number="$suspendedStockists" class="bg-tertiary" />
            </div>
        </div>
    </div>

    <div class="card border shadow-sm">
        <div class="card-header bg-white d-block d-md-flex align-items-center justify-content-between p-3">
            <h5 class="mb-2 mb-md-0">{{ __("available") }}</h5>

            <div class="d-flex align-items-center gap-1">
                <a href="/{{ App::currentLocale() }}/admin/stockists/transfer" class="btn btn-link">
                    {{ __("wallet_transfer") }} <i class="bi bi-arrow-right-short"></i>
                </a>
                <a href="/{{ App::currentLocale() }}/admin/stockists/create" class="btn btn-link">
                    {{ __("add_stockist") }} <i class="bi bi-arrow-right-short"></i>
                </a>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover display" id="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __("name") }}</th>
                            <th>Code</th>
                            <th>{{ __("amount") }}</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stockists as $stockist)
                            <tr>
                                <td>{{ $stockist->user->id }}</td>
                                <td>{{ $stockist->user->name }}</td>
                                <td>{{ $stockist->code }}</td>
                                <td>{{ $stockist->wallet }}</td>
                                <td>
                                    <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="{{ __("view") }}">
                                        <a href="/{{App::currentLocale()}}/admin/stockists/{{ $stockist->user->id }}" class="action-btn text-primary rounded">
                                            <i class="bi bi-eye"></i>
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
                $("#table").DataTable();
            });
        </script>
    @endpush
</x-layout.admin>
