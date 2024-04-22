<x-layout.admin>
    <x-slot name="title">Leadership Bonus</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-2 mb-md-0">{{ __("leadership_bonus") }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("leadership_bonus") }}</li>
            </ol>
          </nav>
    </div>

    <div class="container-fluid p-0 mb-3">
        <div class="row">
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $firstHeading = __("available"); @endphp
                <x-model-summary :title="$firstHeading" icon="list-ol" :number="count($qualifiedUplines)" class="bg-main"/>
            </div>
        </div>
    </div>

    {{-- Qualified Distirbutors --}}
    <div class="card border shadow-sm mb-3 mb-xxl-4">
        <div class="card-header bg-white p-3 d-block d-md-flex align-items-center justify-content-between">
            <h5 class="mb-2 mb-md-0">{{ __("available") }}</h5>
            <div class="d-flex gap-3 align-items-center">
                @if(count($qualifiedUplines) > 0)
                    <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#exampleModal"
                        onclick="setFormAction('/{{ App::currentLocale() }}/admin/leadership-bonus/reset-unqualified')">
                        <i class="bi bi-check2-square"></i> Pay All
                    </button>
                @endif
            </div>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover display" id="qualified-table">
                    <thead>
                        <tr>
                            <th>{{ __("distributor") }}</th>
                            <th>{{ __("package") }}</th>
                            <th>{{ __("weekly_bv_point") }}</th>
                            <th>{{ __("amount") }}</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($qualifiedUplines as $upline)
                            <tr>
                                <td>{{ $upline->user->name }}</td>
                                <td>{{ $upline->user->distributor->registrationPackage->name }}</td>
                                <td>{{ number_format($upline->weekly_point) }}</td>
                                <td>
                                    @php
                                        $rate = 0;

                                        if ($upline->user->distributor->registrationPackage->id === 3) {
                                            $rate = 0.02;
                                        } else if ($upline->user->distributor->registrationPackage->id === 2) {
                                            $rate = 0.03;
                                        } else {
                                            $rate = 0.05;
                                        }

                                        $amount = $upline->weekly_point * $rate;
                                    @endphp
                                    {{ "$" . number_format($amount, 2) }}</td>
                                <td>
                                    <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="{{ __("transfer_commission") }}">
                                        <button class="action-btn text-success rounded" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                            onclick="setFormAction('/{{ App::currentLocale() }}/admin/leadership-bonus/{{ $upline->id }}/pay')">
                                            <i class="bi bi-check2"></i>
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __("leadership_bonus") }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="form">
                    @csrf

                    <div class="modal-body">
                        <p>{{ __("leadership_description") }}?</p>
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
                $("#qualified-table").DataTable();
            });
        </script>
    @endpush
</x-layout.admin>
