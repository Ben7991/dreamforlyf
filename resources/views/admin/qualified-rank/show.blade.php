<x-layout.admin>
    <x-slot name="title">Qualified Pool</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-2 mb-md-0">{{ __("qualified_rank_details") }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("qualified_ranks") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("details") }}</li>
            </ol>
          </nav>
    </div>

    @php $goBackHeading = __("qualified_ranks") @endphp
    <x-go-back path="/{{ App::currentLocale() }}/admin/qualified-ranks" :title="$goBackHeading" />

    <div class="card mb-3 mb-xxl-4">
        <div class="card-header p-3 bg-white">
            <h5 class="m-0">{{ __("details") }}</h5>
        </div>
        <div class="card-body">
            <form action="/{{ App::currentLocale() }}/admin/qualified-ranks/{{ $record_id }}/award" method="POST">
                @csrf
                @method("PUT")

                <div class="row">
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="date_time">{{ __("date_time") }}</label>
                        <input type="datetime" id="date_time" class="form-control" value="{{ $date_time }}" readonly>
                    </div>
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="rank">{{ __("rank") }}</label>
                        <input type="text" id="rank" class="form-control" value="{{ $rank->name }}" readonly>
                    </div>
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="award">{{ __("award") }}</label>
                        <input type="text" id="award" class="form-control" value="{{ $rank->award }}" readonly>
                    </div>
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="point">Rank Point</label>
                        <input type="text" id="point" class="form-control" value="{{ number_format($rank->bv_point) }}" readonly>
                    </div>
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="distributor">{{ __("distributor") }}</label>
                        <input type="text" id="distributor" class="form-control" value="{{ $user->name }}" readonly>
                    </div>
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="left">{{ __("left_leg") }}</label>
                        <input type="text" id="left" class="form-control" value="{{ number_format($user->upline->first_leg_point) }}" readonly>
                    </div>
                    <div class="col-12 col-md-4 col-xl-3 mb-3 mb-xxl-4">
                        <label for="right">{{ __("right_leg") }}</label>
                        <input type="text" id="right" class="form-control" value="{{ number_format($user->upline->second_leg_point) }}" readonly>
                    </div>
                </div>

                @if($status === "PENDING")
                    <button class="btn btn-success" type="submit">
                        <i class="bi bi-check2"></i> {{ __('award') }}
                    </button>
                @endif
            </form>
        </div>
    </div>

</x-layout.admin>
