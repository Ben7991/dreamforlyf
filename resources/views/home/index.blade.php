<x-layout.home>
    <x-slot name="title">Home</x-slot>

    <section class="hero bg-main">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-6">
                    <div>
                        <h1 class="text-white mb-3 mb-xl-4">{{ __("who_are_we") }}?</h1>
                        <p class="text-light description">{{ __("home_info_desc_1") }}</p>
                        <p class="text-light description">{{ __("home_info_desc_2") }}</p>
                        <a href="/{{ App::currentLocale() }}/contact-us" class="btn btn-danger">
                            {{ __("contact_us") }} <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="hero-img-container mt-4 mt-md-0">
                        <img src="{{ asset("assets/img/dreamforlyf-2.jpg") }}" alt="Hero Image" class="hero-img hero-img-top rounded shadow">
                        <img src="{{ asset("assets/img/dreamforlyf-1.jpg") }}" alt="Hero Image" class="hero-img hero-img-bottom rounded shadow">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="dfl">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-6 mb-3 mb-md-0">
                    <div>
                        <h3 class="mb-3 text-center">{{ __("about_us") }}</h3>
                        <x-underline />
                        <p class="description text-secondary">{{ __("home_about_desc") }}</p>
                        <div class="text-center">
                            <a href="/{{ App::currentLocale() }}/about-us" class="btn btn-primary">
                                {{ __("read_more") }} <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
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
                <div class="col-12 col-md-6 mb-3 mb-md-0">
                    <div>
                        <h3 class="mb-3 text-center">{{ __("products") }}</h3>
                        <x-underline />
                        <p class="description text-secondary">{{ __("home_product_desc") }}</p>
                        <div class="text-center">
                            <a href="/{{ App::currentLocale() }}/products" class="btn btn-primary">
                                {{ __("read_more") }} <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 mb-4 mb-md-0">
                    <div class="dfl-img-container">
                        <img src="{{ asset("assets/img/product-1.jpg") }}" alt="Hero Image" class="dfl-img dfl-img-top rounded shadow">
                        <img src="{{ asset("assets/img/product-2.jpg") }}" alt="Hero Image" class="dfl-img dfl-img-left rounded shadow">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="dfl">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-6 mb-3 mb-md-0">
                    <div>
                        <h3 class="mb-3 text-center">{{ __("opportunity") }}</h3>
                        <x-underline />
                        <p class="description text-secondary">{{ __("home_opportunity_desc") }}</p>
                        <div class="text-center">
                            <a href="/{{ App::currentLocale() }}/opportunity" class="btn btn-primary">
                                {{ __("read_more") }} <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 mb-4 mb-md-0">
                    <div class="dfl-img-container">
                        <img src="{{ asset("assets/img/dreamforlyf-8.jpg") }}" alt="Hero Image" class="dfl-img dfl-img-top rounded shadow">
                        <img src="{{ asset("assets/img/dreamforlyf-5.jpg") }}" alt="Hero Image" class="dfl-img dfl-img-left rounded shadow">
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-layout.home>
