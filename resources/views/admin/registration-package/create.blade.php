<x-layout.admin>
    <x-slot name="title">Registration Packages</x-slot>

    <div class="d-block d-md-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-2 mb-md-0">{{ __("registration_packages") }}</h4>
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin">{{ __("home") }}</a></li>
              <li class="breadcrumb-item"><a href="/{{ App::currentLocale() }}/admin/registration-packages">{{ __("registration_packages") }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ __("create_package") }}</li>
            </ol>
          </nav>
    </div>

    @php
        $header = __("registration_packages");
    @endphp

    <x-go-back path="/{{ App::currentLocale() }}/admin/registration-packages" :title="$header" />

    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12 col-md-6 col-xl-5 col-xxl-4">
                <div class="border bg-white rounded shadow-sm p-3">
                    <h5 class="mb-3">{{ __("create_package") }}</h5>

                    <ul>
                        @foreach($errors->all() as $message)
                            <li class="text-danger">{{ $message }}</li>
                        @endforeach
                    </ul>

                    <form action="/{{App::getLocale()}}/admin/registration-packages" method="POST" id="form">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="name">{{ __("name") }}</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <small class="text-danger d-none"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="price">{{ Str::ucfirst(__("prix")) }}</label>
                            <input type="text" name="price" id="price" class="form-control">
                            <small class="text-danger d-none"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="bv_point">BV Point</label>
                            <input type="text" name="bv_point" id="bv_point" class="form-control">
                            <small class="text-danger d-none"></small>
                        </div>
                        <div class="form-group mb-4">
                            <label for="cutoff">{{ __("cutoff") }}</label>
                            <input type="text" name="cutoff" id="cutoff" class="form-control">
                            <small class="text-danger d-none"></small>
                        </div>

                        <button class="btn btn-success" type="submit">
                            <i class="bi bi-save"></i> {{ __("save") }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push("scripts")
        <script src="{{ asset("assets/js/admin/registration-package/create.js") }}"></script>
    @endpush
</x-layout.admin>
