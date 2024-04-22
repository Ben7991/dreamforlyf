<x-layout.admin>
    <x-slot name="title">Qualified Pool</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-2 mb-md-0">{{ __("qualified_pool") }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("qualified_pool") }}</li>
            </ol>
          </nav>
    </div>

    <div class="container-fluid p-0 mb-3">
        <div class="row">
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $firstHeading = __("available"); @endphp
                <x-model-summary :title="$firstHeading" icon="list-ol" :number="$total" class="bg-main"/>
            </div>
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $secondHeading = __("pending"); @endphp
                <x-model-summary :title="$secondHeading" icon="clock-history" :number="$pending" class="bg-tertiary" />
            </div>
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $thirdHeading = __("awarded"); @endphp
                <x-model-summary :title="$thirdHeading" icon="check2-circle" :number="$awarded" class="bg-other" />
            </div>
        </div>
    </div>

    <div class="card border shadow-sm">
        <div class="card-header bg-white p-3">
            <h5 class="mb-2 mb-md-0">{{ __("available") }}</h5>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover display" id="product-table">
                    <thead>
                        <tr>
                            <th>{{ __("date_time") }}</th>
                            <th>{{ __("distributor_id") }}</th>
                            <th>{{ __("distributor") }}</th>
                            <th>{{ __("status") }}</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($poolRecords as $poolRecord)
                            <tr>
                                <td>{{ $poolRecord->created_at }}</td>
                                <td>{{ $poolRecord->upline->user->id }}</td>
                                <td>{{ $poolRecord->upline->user->name }}</td>
                                <td>{{ $poolRecord->status }}</td>
                                <td>
                                    <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="{{ __("view") }}">
                                        <a href="/{{App::currentLocale()}}/admin/qualified-pool/{{ $poolRecord->id }}" class="action-btn text-primary rounded">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </span>
                                    @if($poolRecord->status === "PENDING")
                                        <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="{{ __("award") }}">
                                            <button class="action-btn text-success rounded" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                onclick="setFormAction('/{{ App::currentLocale() }}/admin/qualified-pool/{{ $poolRecord->id }}/award')">
                                                <i class="bi bi-check2"></i>
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __("give_award") }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="form">
                    @csrf
                    @method("PUT")

                    <div class="modal-body">
                        <p>{{ __("give_award_description") }}?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("close") }}</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check2"></i> {{ __("award") }}
                        </button>
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
