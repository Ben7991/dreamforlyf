<x-layout.admin>
    <x-slot name="title">Bonus Withdrawals</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-2 mb-md-0">{{ __("bonus_withdrawal") }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("bonus_withdrawal") }}</li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid p-0 mb-3">
        <div class="row">
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $firstHeading = __("total_withdrawals"); @endphp
                <x-model-summary :title="$firstHeading" icon="list-ol" :number="$total" class="bg-main"/>
            </div>
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $secondHeading = __("pending"); @endphp
                <x-model-summary :title="$secondHeading" icon="clock-history" :number="$pending" class="bg-tertiary" />
            </div>
            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                @php $thirdHeading = __("approved"); @endphp
                <x-model-summary :title="$thirdHeading" icon="check2-circle" :number="$approved" class="bg-other" />
            </div>
        </div>
    </div>

    <div class="card border shadow-sm">
        <div class="card-header bg-white p-3 d-block d-md-flex align-items-center justify-content-between">
            <h5 class="mb-2 mb-md-0">{{ __("available") }}</h5>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  {{ __("filter_by") }}
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="/{{ App::currentLocale() }}/admin/bonus-withdrawals">{{ strtoupper(__("all") ) }}</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item" href="/{{ App::currentLocale() }}/admin/bonus-withdrawals/filter?status=PENDING">{{ strtoupper(__("pending")) }}</a></li>
                  <li><a class="dropdown-item" href="/{{ App::currentLocale() }}/admin/bonus-withdrawals/filter?status=APPROVED">{{ strtoupper(__("approved")) }}</a></li>
                </ul>
              </div>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover display" id="product-table">
                    <thead>
                        <tr>
                            <th>{{ __("date_time") }}</th>
                            <th>{{ __("withdrawal_amount") }}</th>
                            <th>{{ __("deduction") }} (5%)</th>
                            <th>{{ __("amount_paid") }}</th>
                            <th>{{ __("distributor") }}</th>
                            <th>{{ __("distributor_id") }}</th>
                            <th>{{ __("city") }}</th>
                            <th>{{ __("wave_number") }}</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($withdrawals as $detail)
                            <tr>
                                <td>{{ $detail->created_at }}</td>
                                <td>${{ $detail->amount }}</td>
                                <td>${{ $detail->deduction }}</td>
                                <td>${{ $detail->amount - $detail->deduction }}</td>
                                <td>{{ $detail->distributor->user->name }}</td>
                                <td>{{ $detail->distributor->user->id }}</td>
                                <td>{{ $detail->distributor->country }}</td>
                                <td>Accra</td>
                                <td>{{ $detail->distributor->wave }}</td>
                                <td>
                                    @if($detail->status === "PENDING")
                                        <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="Give award">
                                            <button class="action-btn text-success rounded" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                onclick="setFormAction('/{{ App::currentLocale() }}/admin/bonus-withdrawals/{{ $detail->id }}/approve')">
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __("approve_withdrawal") }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form" method="post">
                    @csrf
                    @method("PUT")

                    <div class="modal-body">
                        <p class="m-0">{{ __("approve_withdrawal_description") }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("close") }}</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check2"></i> {{ __("approve_withdrawal") }}
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
