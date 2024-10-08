<x-layout.distributor>
    <x-slot name="title">My Tree</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3 mb-xxl-4">
        <h4 class="mb-2 mb-md-0">{{ $user->name }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/distributor">{{ __("home") }}</a></li>
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/distributor/my-tree">{{ __("my_tree") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("downline_details") }}</li>
            </ol>
        </nav>
    </div>

    <x-go-back path="/{{ App::currentLocale() }}/distributor/my-tree" title="My Tree"/>

    <div class="container-fluid p-0 mb-3">
        <div class="row">
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $firstHeading = __("membership_package"); @endphp
                <x-model-summary :title="$firstHeading" icon="award" :number="$membershipPackage" class="bg-main" />
            </div>
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $secondHeading = __("total_left_bv"); @endphp
                <x-model-summary :title="$secondHeading" icon="filter-left" :number="$totalLeftLeg" class="bg-tertiary" />
            </div>
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $thirdHeading = __("total_right_bv"); @endphp
                <x-model-summary :title="$thirdHeading" icon="filter-right" :number="$totalRightLeg" class="bg-other" />
            </div>
        </div>
    </div>

    <input type="hidden" id="locale" value="{{ App::currentLocale() }}">

    @php
        function swap($distributors) {
            $newDistributors = ['1st', '2nd'];

            foreach ($distributors as $distributor) {
                for($j = 0; $j < 2; $j++) {
                    if ($newDistributors[$j] === $distributor->leg) {
                        $newDistributors[$j] = $distributor;
                    }
                }
            }

            return $newDistributors;
        }
    @endphp

    <div class="card border shadow-sm">
        <div class="card-header bg-white p-3 d-block d-md-flex align-items-center justify-content-between">
            <h5 class="mb-2 mb-md-0">{{ $user->name }}  {{ __("distributors") }}</h5>
        </div>
        <div class="card-body p-3">
            <div class="d-block d-md-flex align-items-center justify-content-between mb-5">
                <div class="col-12 col-md-5 col-xl-4 col-xxl-3 mb-3 mb-md-0">
                    <label for="link" class="mb-2 text-secondary">{{ __("left_referral_link") }}</label>
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control" id="link" readonly
                            value="http://localhost:8000/{{ App::currentLocale() }}/sponsor?id={{ $user->id}}&token={{ $token }}&side=left">
                        <button class="btn btn-secondary position-relative btn-referral-link">
                            <span class="link-response">
                                <span class="link-response-holder">{{ __("copied") }}</span>
                            </span>
                            <i class="bi bi-clipboard-check"></i>
                        </button>
                    </div>
                </div>
                <div class="col-12 col-md-5 col-xl-4 col-xxl-3 mb-3 mb-md-0">
                    <label for="link" class="mb-2 text-secondary">{{ __("right_referral_link") }}</label>
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control" id="link" readonly
                            value="http://localhost:8000/{{ App::currentLocale() }}/sponsor?id={{ $user->id}}&token={{ $token }}&side=right">
                        <button class="btn btn-secondary position-relative btn-referral-link">
                            <span class="link-response">
                                <span class="link-response-holder">{{ __("copied") }}</span>
                            </span>
                            <i class="bi bi-clipboard-check"></i>
                        </button>
                    </div>
                </div>
                <div class="col-12 col-md-5 col-xl-4 col-xxl-3">
                    <label for="search" class="mb-2 text-secondary">{{ __("search") }}</label>
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control" id="input-search" placeholder="{{ __("search_by_id") }}">
                        <button class="btn btn-secondary" id="btn-search">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="tree mb-5">
                <div class="position-relative d-flex justify-content-center mb-3">
                    <div class="tree-img d-flex align-items-center justify-content-center {{ $user->image === null ? '' : 'd-none' }} profile-placeholder">
                        <i class="bi bi-person fs-2"></i>
                    </div>
                    <img src="{{ asset(Str::replaceFirst('public', 'storage', $user->image)) }}" alt="User Image"
                        class="tree-img d-block {{ $user->image === null ? 'd-none' : '' }} profile-image">
                    @if($user->upline !== null && count($user->upline->distributors) > 0)
                        <div class="tree-connector"></div>
                    @endif
                </div>
                @if($user->upline !== null)
                    <div class="tree-downlines">
                        <div class="d-flex justify-content-between position-relative pt-3">
                            <div class="tree-downline-top-connector"></div>
                            @foreach(swap($user->upline->distributors) as $distributor)
                                <div class="position-relative w-50">
                                    @if ($distributor !== "1st" && $distributor !== "2nd")
                                        <div class="tree-img downline mx-auto d-flex align-items-center justify-content-center {{ $distributor->user->image === null ? '' : 'd-none' }} profile-placeholder"
                                            data-id="{{ $distributor->id }}" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            <i class="bi bi-person fs-4"></i>
                                        </div>
                                        <img src="{{ asset(Str::replaceFirst('public', 'storage', $distributor->user->image)) }}" alt="User Image"
                                            class="tree-img downline d-block mx-auto {{ $distributor->user->image === null ? 'd-none' : '' }} profile-image"
                                            data-id="{{ $distributor->id }}" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        <div class="tree-downline-connector top"></div>

                                        @if($distributor->user->upline !== null && count($distributor->user->upline->distributors))
                                            <div class="tree-downline-connector bottom"></div>
                                            <div class="d-flex justify-content-between mt-4">
                                                @php
                                                    $upline = $distributor->user->upline;
                                                @endphp
                                                <div class="d-flex justify-content-between w-100 position-relative pt-3">
                                                    <div class="tree-downline-top-connector w-2nd"></div>
                                                    @foreach(swap($upline->distributors) as $dist)
                                                        <div class="w-50 position-relative">
                                                            @if ($dist !== "1st" && $dist !== "2nd")
                                                                <div class="tree-img downline mx-auto d-flex align-items-center justify-content-center {{ $dist->user->image === null ? '' : 'd-none' }} profile-placeholder"
                                                                    data-id="{{ $dist->id }}" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                                    <i class="bi bi-person fs-4"></i>
                                                                </div>
                                                                <img src="{{ asset(Str::replaceFirst('public', 'storage', $dist->user->image)) }}" alt="User Image"
                                                                    class="tree-img downline d-block mx-auto {{ $dist->user->image === null ? 'd-none' : '' }} profile-image"
                                                                    data-id="{{ $dist->id }}" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                                <div class="tree-downline-connector top"></div>

                                                                @if($dist->user->upline !== null && count($dist->user->upline->distributors))
                                                                    <div class="tree-downline-connector bottom"></div>
                                                                    <div class="d-flex justify-content-between mt-4">
                                                                        @php
                                                                            $upline = $dist->user->upline;
                                                                        @endphp
                                                                        <div class="d-flex justify-content-between w-100 position-relative pt-3">
                                                                            <div class="tree-downline-top-connector w-3rd"></div>
                                                                            @foreach(swap($upline->distributors) as $distrib)
                                                                                <div class="w-50 position-relative">
                                                                                    @if ($distrib !== "1st" && $distrib !== "2nd")
                                                                                        <div class="tree-img downline mx-auto d-flex align-items-center justify-content-center {{ $distrib->user->image === null ? '' : 'd-none' }} profile-placeholder"
                                                                                            data-id="{{ $distrib->id }}" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                                                            <i class="bi bi-person fs-4"></i>
                                                                                        </div>
                                                                                        <img src="{{ asset(Str::replaceFirst('public', 'storage', $distrib->user->image)) }}" alt="User Image"
                                                                                            class="tree-img downline d-block mx-auto {{ $distrib->user->image === null ? 'd-none' : '' }} profile-image"
                                                                                            data-id="{{ $distrib->id }}" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                                                        <div class="tree-downline-connector top"></div>
                                                                                    @endif
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Loading...</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick=""></button>
                </div>
                <div class="modal-body mb-4">
                    <div class="text-center" id="spinner-holder">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div id="modal-content-description" class="d-none">
                        <div class="d-flex gap-3">
                            <img alt="" id="modal-img" width="70px" height="70px" class="object-fit-cover rounded-circle">
                            <input type="hidden" id="person-svg" value="{{ asset("assets/img/person.svg") }}">
                            <div>
                                <h5 id="modal-downline-name">James Williams</h5>
                                <p id="modal-downline-package" class="m-0"></p>
                            </div>
                        </div><hr>
                        <div class="d-flex flex-wrap justify-content-between mb-3">
                            <div class="py-2 px-3 rounded border col-5 mb-3">
                                <h6>{{ __("total_left_bv") }}</h6>
                                <p id="leftBvPoint" class="m-0"></p>
                            </div>
                            <div class="py-2 px-3 rounded border col-5 mb-3">
                                <h6>{{ __("total_right_bv") }}</h6>
                                <p id="rightBvPoint" class="m-0"></p>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap justify-content-between mb-3">
                            <div class="py-2 px-3 rounded border col-5 mb-3">
                                <h6>{{ __("left_distributor_no") }}</h6>
                                <p id="leftBvDist" class="m-0"></p>
                            </div>
                            <div class="py-2 px-3 rounded border col-5 mb-3">
                                <h6>{{ __("right_distributor_no") }}</h6>
                                <p id="rightBvDist" class="m-0"></p>
                            </div>
                        </div>
                        <a class="link-opacity-75-hover" id="modal-link">
                            <i class="bi bi-arrow-right"></i> {{ __("see_my_tree") }}
                        </a>
                    </div>
                    <p id="modal-error-description" class="d-none">{{ __("my_tree_error") }}</p>
                </div>
            </div>
        </div>
    </div>


    @push("scripts")
        <script src="{{ asset("assets/js/distributor/my-tree/index.js") }}"></script>
        <script src="{{ asset("assets/js/distributor/reset-maintenance-store.js") }}"></script>
    @endpush
</x-layout.distributor>
