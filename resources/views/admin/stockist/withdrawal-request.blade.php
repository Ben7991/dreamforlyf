<x-layout.admin>
    <x-slot name="title">Stockist Withdrawals</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-2 mb-md-0">{{ __("total_requests") }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="/{{ App::currentLocale() }}/admin/stockist-withdrawals">{{ __("stockist_withdrawal") }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ __("total_requests") }}</li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid p-0 mb-3">
        <div class="row">
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $firstHeading = __("total_requests"); @endphp
                <x-model-summary :title="$firstHeading" icon="list-ol" :number="$total" class="bg-main"/>
            </div>
        </div>
    </div>

    <div class="card border shadow-sm">
        <div class="card-header bg-white py-4 px-3">
            <h5 class="mb-2 mb-md-0">{{ __("available") }}</h5>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover display" id="product-table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $detail)
                            <tr>
                                <td>{{ $detail->code }}</td>
                                <td>
                                    <form action="/{{ App::currentLocale()}}/admin/stockist-withdrawals/request/{{ $detail->id }}" method="POST">
                                        @csrf
                                        @method("PUT")
                                        <button class="btn btn-primary btn-sm">
                                            <i class="bi bi-check2"></i> Approve
                                        </button>
                                    </form>
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
