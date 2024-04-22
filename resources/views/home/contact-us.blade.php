<x-layout.home>
    <x-slot name="title">Contact</x-slot>

    <section class="bg-main dfl">
        <div class="container">
            <h1 class="text-white mb-3 mb-xl-4">{{ __("contact_us") }}</h1>
            <p class="description text-light">{{ __("contact_us_info") }}</p>
        </div>
    </section>

    <section class="dfl">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-6">
                    <img src="{{ asset("assets/img/dreamforlyf-6.jpg") }}" alt="" class="w-100 object-fit-cover rounded shadow">
                </div>
                <div class="col-12 col-md-6">
                    <x-alert />

                    <h5 class="mb-3 text-center">{{ __("contact_form_heading") }}</h5>
                    <x-underline />
                    <form action="/{{ App::currentLocale() }}/contact-us" method="POST">
                        @csrf

                        <div class="form-group mb-3 mb-xxl-4">
                            <label for="name" class="text-secondary mb-1">{{ __("name") }}</label>
                            <input type="text" name="name" id="name" class="form-control">
                        </div>
                        <div class="form-group mb-3 mb-xxl-4">
                            <label for="subject" class="text-secondary mb-1">{{ __("subject") }}</label>
                            <input type="text" name="subject" id="subject" class="form-control">
                        </div>
                        <div class="form-group mb-3 mb-xxl-4">
                            <label for="email" class="text-secondary mb-1">{{ __("email") }}</label>
                            <input type="email" name="email" id="email" class="form-control">
                        </div>
                        <div class="form-group mb-3 mb-xxl-4">
                            <label for="message" class="text-secondary mb-1">{{ __("message") }}</label>
                            <textarea name="message" id="message" rows="5" class="form-control"></textarea>
                        </div>
                        <div class="d-grid w-50 mx-auto">
                            <button class="btn btn-block btn-success" type="submit">
                                {{ __("submit") }} <i class="bi bi-send"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

</x-layout.home>
