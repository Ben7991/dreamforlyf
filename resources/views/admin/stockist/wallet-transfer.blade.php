<x-layout.admin>
    <x-slot name="title">Stockists</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-2 mb-md-0">{{ __("wallet_transfer") }}</h4>

        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
                <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin/distributors">{{ __("distributors") }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __("wallet_transfer") }}</li>
            </ol>
        </nav>
    </div>

    @php $goBackHeading = __("distributors") @endphp
    <x-go-back path="/{{ App::currentLocale() }}/admin/stockists" :title="$goBackHeading" />

    <div class="container-fluid p-0 mb-3">
        <div class="row">
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $firstHeading = __("available"); @endphp
                <x-model-summary :title="$firstHeading" icon="list-ol" :number="$totalTransfer" class="bg-main"/>
            </div>
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $secondHeading = __("amount_transfered"); @endphp
                <x-model-summary :title="$secondHeading" icon="person-fill-slash" :number="$totalAmountTransfered" class="bg-tertiary" />
            </div>
        </div>
    </div>

    <div class="card border shadow-sm">
        <div class="card-header bg-white d-block d-md-flex align-items-center justify-content-between p-3">
            <h5 class="mb-2 mb-md-0">{{ __("available") }}</h5>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover display" id="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __("date_time") }}</th>
                            <th>Code</th>
                            <th>{{ __("amount_transfered") }}</th>
                            <th>{{ __("status") }}</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transfers as $transfer)
                            <tr>
                                <td>{{ $transfer->id }}</td>
                                <td>{{ $transfer->date_added }}</td>
                                <td>{{ $transfer->code }}</td>
                                <td>$ {{ number_format($transfer->amount, 2) }}</td>
                                <td>{{ $transfer->status }}</td>
                                <td>
                                    @if ($transfer->status === "COMPLETE")
                                        <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="{{ __("reverse_transfer") }}">
                                            <button onclick="setFormAction('/{{App::currentLocale()}}/admin/stockists/{{ $transfer->id }}/reverse-transfer')"
                                                type="button" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                class="action-btn text-primary rounded">
                                                <i class="bi bi-arrow-90deg-right"></i>
                                            </button>
                                        </span>
                                    @endif
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __("reverse_transfer") }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="form">`
                    @csrf
                    @method('PUT')

                    <div class="modal-body">
                        <p class="m-0">Are you sure you want to reverse this transfer?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("close") }}</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check2"></i> {{ __("yes") }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push("scripts")
        <script>
            $(document).ready(function() {
                $("#table").DataTable({
                    ordering: false
                });
            });
        </script>
    @endpush
</x-layout.admin>
