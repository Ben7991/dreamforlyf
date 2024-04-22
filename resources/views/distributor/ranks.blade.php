<x-layout.distributor>
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
        $leftPoint = Auth::user()->upline !== null ? Auth::user()->upline->first_leg_point : 0;
        $rightPoint = Auth::user()->upline !== null ? Auth::user()->upline->second_leg_point : 0;
    @endphp

    <div class="container-fluid p-0 mb-3">
        <div class="row">
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $firstHeading = __("current_rank"); @endphp
                <x-model-summary :title="$firstHeading" icon="award" number="None" class="bg-main" />
            </div>
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $secondHeading = __("left") . " Bv Point"; @endphp
                <x-model-summary :title="$secondHeading" icon="filter-left" :number="number_format($leftPoint)" class="bg-tertiary" />
            </div>
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $thirdHeading = __("right") . " Bv Point"; @endphp
                <x-model-summary :title="$thirdHeading" icon="filter-right" :number="number_format($rightPoint)" class="bg-other" />
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
                            <th>{{ __("name") }}</th>
                            <th>Points</th>
                            <th>{{ __("award") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ranks as $rank)
                            <tr>
                                <td>{{ $rank->id }}</td>
                                <td>{{ $rank->name }}</td>
                                <td>{{ number_format($rank->bv_point) }}</td>
                                <td>{{ $rank->award }}</td>
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
