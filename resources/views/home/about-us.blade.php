<x-layout.home>
    <x-slot name="title">About</x-slot>

    <section class="bg-main dfl">
        <div class="container">
            <h1 class="text-white mb-3 mb-xl-4">{{ __("about_us") }}</h1>
            <p class="description text-light">{{ __("about_us_info") }}</p>
        </div>
    </section>

    <section class="dfl">
        <div class="container">
            <h3 class="text-center mb-3">{{ __("our_core_values") }}</h3>
            <x-underline />
            <div class="row">
                <div class="col-12 col-md-4">
                    <span class="rounded bg-main text-white py-2 px-3 d-inline-block shadow">
                        <i class="bi bi-bullseye fs-3"></i>
                    </span>
                    <h3 class="my-3">{{ __("integrity") }}</h3>
                    <p class="text-secondary description">{{ __("integrity_message") }}</p>
                </div>
                <div class="col-12 col-md-4">
                    <span class="rounded bg-main text-white py-2 px-3 d-inline-block shadow">
                        <i class="bi bi-calendar-range fs-3"></i>
                    </span>
                    <h3 class="my-3">{{ __("sustainability") }}</h3>
                    <p class="text-secondary description">{{ __("sustainability_message") }}</p>
                </div>
                <div class="col-12 col-md-4">
                    <span class="rounded bg-main text-white py-2 px-3 d-inline-block shadow">
                        <i class="bi bi-gear fs-3"></i>
                    </span>
                    <h3 class="my-3">{{ __("service") }}</h3>
                    <p class="text-secondary description">{{ __("service_message") }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="dfl bg-light-subtle text-light-emphasis">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-6">
                    <div>
                        <h3 class="mb-3 text-center">{{ __("mission") }}</h3>
                        <x-underline />
                        <p class="description text-secondary">{{ __("mission_message") }}</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 mb-4 mb-md-0">
                    <div class="dfl-img-container">
                        <img src="{{ asset("assets/img/dreamforlyf-7.jpg") }}" alt="Hero Image" class="dfl-img dfl-img-top rounded shadow">
                        <img src="{{ asset("assets/img/dreamforlyf-8.jpg") }}" alt="Hero Image" class="dfl-img dfl-img-left rounded shadow">
                        <img src="{{ asset("assets/img/dreamforlyf-9.jpg") }}" alt="Hero Image" class="dfl-img dfl-img-right rounded shadow">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="dfl">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-6">
                    <div>
                        <h3 class="mb-3 text-center">{{ __("vision") }}</h3>
                        <x-underline />
                        <p class="description text-secondary">{{ __("vision_message") }}</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 mb-4 mb-md-0">
                    <div class="dfl-img-container">
                        <img src="{{ asset("assets/img/dreamforlyf-10.jpg") }}" alt="Hero Image" class="dfl-img dfl-img-top rounded shadow">
                        <img src="{{ asset("assets/img/dreamforlyf-11.jpg") }}" alt="Hero Image" class="dfl-img dfl-img-left rounded shadow">
                        <img src="{{ asset("assets/img/dreamforlyf-12.jpg") }}" alt="Hero Image" class="dfl-img dfl-img-right rounded shadow">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="dfl bg-light-subtle text-light-emphasis">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-6">
                    <div>
                        <h3 class="mb-3 text-center">{{ __("rytsh") }}</h3>
                        <x-underline />
                        <p class="description text-secondary">{{ __("rytsh_message") }}</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 mb-4 mb-md-0">
                    <div class="dfl-img-container">
                        <img src="{{ asset("assets/img/dreamforlyf-13.jpg") }}" alt="Hero Image" class="dfl-img dfl-img-top rounded shadow">
                        <img src="{{ asset("assets/img/dreamforlyf-17.jpg") }}" alt="Hero Image" class="dfl-img dfl-img-left rounded shadow">
                        <img src="{{ asset("assets/img/dreamforlyf-5.jpg") }}" alt="Hero Image" class="dfl-img dfl-img-right rounded shadow">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="dfl">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-6">
                    <div>
                        <h3 class="mb-3 text-center">{{ __("in_service") }}</h3>
                        <x-underline />
                        <p class="description text-secondary">{{ __("in_service_message") }}</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 mb-4 mb-md-0">
                    <div class="dfl-img-container">
                        <img src="{{ asset("assets/img/dreamforlyf-16.jpg") }}" alt="Hero Image" class="dfl-img dfl-img-top rounded shadow">
                        <img src="{{ asset("assets/img/dreamforlyf-14.jpg") }}" alt="Hero Image" class="dfl-img dfl-img-left rounded shadow">
                        <img src="{{ asset("assets/img/dreamforlyf-15.jpg") }}" alt="Hero Image" class="dfl-img dfl-img-right rounded shadow">
                    </div>
                </div>
            </div>
        </div>
    </section>


</x-layout.home>
