<x-layout.admin>
    <x-slot name="title">Stockists</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-2 mb-md-0">{{ __("add_stockist") }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin/stockists">{{ __("stockists") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("add_stockist") }}</li>
            </ol>
          </nav>
    </div>

    @php $goBackHeading = __("stockists") @endphp
    <x-go-back path="/{{ App::currentLocale() }}/admin/stockists" :title="$goBackHeading" />

    <div class="container-fluid p-3 bg-white rounded border shadow-sm mb-4">
        <h5 class="mb-3">{{ __("details") }}</h5>

        <ul>
            @foreach($errors->all() as $message)
                <li class="text-danger">{{ $message }}</li>
            @endforeach
        </ul>

        <form action="/{{ App::currentLocale() }}/admin/stockists" method="POST" id="form">
            @csrf

            <div class="row mb-3">
                <div class="col-12 col-md-6 col-lg-4 col-xxl-3 mb-3">
                    <label for="name">{{ __("name") }}</label>
                    <input type="text" name="name" id="name" class="form-control" >
                    <small class="text-danger d-none"></small>
                </div>
                <div class="col-12 col-md-6 col-lg-4 col-xxl-3 mb-3">
                    <label for="email">{{ __("email") }}</label>
                    <input type="email" name="email" id="email" class="form-control">
                    <small class="text-danger d-none"></small>
                </div>
                <div class="col-12 col-md-6 col-lg-4 col-xxl-3 mb-3">
                    <label for="country">{{ __("country") }}</label>
                    <select name="country" id="country" class="form-select">
                        <option value="">{{ __("select_country") }}</option>
                    </select>
                    <small class="text-danger d-none"></small>
                </div>
                <div class="col-12 col-md-6 col-lg-4 col-xxl-3 mb-3">
                    <label for="city">{{ __("city") }}</label>
                    <input type="text" name="city" id="city" class="form-control">
                    <small class="text-danger d-none"></small>
                </div>
                <div class="col-12 col-md-6 col-lg-4 col-xxl-3 mb-3">
                    <label for="code">Code</label>
                    <input type="text" name="code" id="code" class="form-control">
                    <small class="text-danger d-none"></small>
                </div>
            </div>

            <button type="submit" class="btn btn-success btn-submit">
                <span class="main-btn">
                    <i class="bi bi-save"></i> {{ __("save") }}
                </span>
                <x-submit-spinner />
            </button>
        </form>
    </div>


    @push("scripts")
        <script src="{{ asset("assets/js/admin/stockist/create.js") }}"></script>
    @endpush
</x-layout.admin>
