<x-layout.distributor>
    <x-slot name="title">Qualified Rank</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-2 mb-md-0">{{ __("qualified_ranks") }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("qualified_ranks") }}</li>
            </ol>
          </nav>
    </div>

    <div class="container-fluid p-0 mb-3">
        <div class="row">
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $firstHeading = __("all"); @endphp
                <x-model-summary :title="$firstHeading" icon="award" :number="$total" class="bg-main" />
            </div>
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $secondHeading = __("pending"); @endphp
                <x-model-summary :title="$secondHeading" icon="clock-history" :number="$pending" class="bg-tertiary" />
            </div>
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $thirdHeading = __("awarded"); @endphp
                <x-model-summary :title="$thirdHeading" icon="check2" :number="$awarded" class="bg-other" />
            </div>
        </div>
    </div>

    <div class="card border shadow-sm">
        <div class="card-header bg-white p-3">
            <h5 class="m-0">{{ __("available") }}</h5>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover display" id="product-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            {{-- <th>{{ __("date_time") }}</th> --}}
                            <th>{{ __("rank") }}</th>
                            <th>{{ __("award") }}</th>
                            <th>{{ __("status") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($qualifiedRanks as $rank)
                            <tr>
                                <td>{{ $rank["id"] }}</td>
                                {{-- <td>{{ $rank->pivot->date_added }}</td> --}}
                                <td>{{ $rank["rank"]->name }}</td>
                                <td>{{ $rank["rank"]->award }}</td>
                                <td>
                                    @if ($rank["status"] === "PENDING")
                                        <span class="badge text-bg-danger">{{ $rank["status"] }}</span>
                                    @else
                                        <span class="badge text-bg-success">{{ $rank["status"] }}</span>
                                    @endif
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
        <script src="{{ asset("assets/js/distributor/reset-maintenance-store.js") }}"></script>
    @endpush
</x-layout.distributor>
