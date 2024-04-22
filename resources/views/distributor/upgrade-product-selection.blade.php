<x-layout.distributor>
    <x-slot name="title">Registration Packages</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3 mb-xxl-4">
        <h4 class="mb-2 mb-md-0">
            {{ __("upgrade") }}
        </h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("membership_package") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("upgrade") }}</li>
            </ol>
        </nav>
    </div>

    @php $goBackHeading = __("membership_package"); @endphp

    <x-go-back path="/{{ App::currentLocale() }}/distributor/membership-packages" :title="$goBackHeading"/>

    <div class="container-fluid p-0 mb-3 mb-xxl-4">
        <div class="row">
            @foreach($upgradeTypes as $upgradeType)
                <div class="col-12 col-md-6 col-xl-4 col-xxl-3">
                    <div class="rounded overflow-hidden">
                        <img src="{{ asset(str_replace("public", "storage", $upgradeType->image)) }}" alt="{{ $upgradeType->type }}" class="w-100 d-block mb-2">
                        <div class="text-center">
                            <button class="btn btn-success btn-stack" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                onclick="setFormAction('/{{App::currentLocale()}}/distributor/membership-packages/upgrade/{{ $upgradeType->id }}/products?next={{ $nextPackage->id }}&token={{ $token }}')">
                                <i class="bi bi-graph-up-arrow"></i> {{ __("upgrade") }}
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __("complete_upgrade_process") }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form" method="post">
                    @csrf

                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="stockist_id">{{ __("select_stockist") }}</label>
                            <select name="stockist_id" id="stockist_id" class="form-select">
                                <option value="">{{ __("select_stockist") }}</option>
                                @foreach($stockists as $stockist)
                                    <option value="{{ $stockist->id }}">{{ $stockist->code }}</option>
                                @endforeach
                            </select>
                            <small class="text-danger d-none"></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("no") }}</button>
                        <button type="submit" class="btn btn-success btn-submit">
                            <span class="main-btn">
                                <i class="bi bi-check2-square"></i> {{ __("complete") }}
                            </span>
                            <x-submit-spinner />
                        </button>
                    </div>
                </form>
            </div>
        </div>
      </div>

    @push("scripts")
        <script src="{{ asset("assets/js/distributor/reset-maintenance-store.js") }}"></script>
        <script src="{{ asset("assets/js/distributor/upgrade.js") }}"></script>
    @endpush
</x-layout.distributor>
