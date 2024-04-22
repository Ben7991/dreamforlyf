<x-layout.home>
    <x-slot name="title">FAQs</x-slot>

    <section class="bg-main dfl">
        <div class="container">
            <h1 class="text-white mb-3 mb-xl-4">{{ __("faqs_heading") }}</h1>
            <p class="description text-light">{{ __("faqs_info") }}</p>
        </div>
    </section>

    <section class="bg-light-subtle text-light-emphasis dfl">
        <div class="container">
            <div class="accordion mb-4 mb-xxl-5" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            {{ __("faqs_question_1") }}
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <p class="description text-secondary">{{ __("faqs_answer_sec_1") }}</p>
                            <p class="description text-secondary">{{ __("faqs_answer_sec_2") }}</p>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            {{ __("faqs_question_2") }}
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <p class="description text-secondary">{{ __("faqs_answer_2_sec_1") }} <a href="mailto: info@dreamforlyf.com">info@dreamforlyfintl.com</a></p>
                            <p class="description text-secondary">{{ __("faqs_answer_2_sec_2") }} <a href="mailto: info@dreamforlyf.com">info@dreamforlyfintl.com</a></p>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            {{ __("faqs_question_3") }}
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <p class="description text-secondary">{{ __("faqs_answer_3_sec_1") }}</p>
                            <p class="description text-secondary">{{ __("faqs_answer_3_sec_2") }}</p>
                            <p class="description text-secondary">{{ __("faqs_answer_3_sec_3") }}</p>
                        </div>
                    </div>
                </div>
            </div>


            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            {{ __("faqs_question_4") }}
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <p class="description text-secondary">{{ __("faqs_answer_4_sec_1") }}</p>
                            <p class="description text-secondary">{{ __("faqs_answer_4_sec_2") }}</p>
                            <p class="description text-secondary">{{ __("faqs_answer_4_sec_3") }}</p>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            {{ __("faqs_question_5") }}
                        </button>
                    </h2>
                    <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <p class="description text-secondary">{{ __("faqs_answer_5_sec_1") }}</p>
                            <p class="description text-secondary">{{ __("faqs_answer_5_sec_2") }}</p>
                            <p class="description text-secondary">{{ __("faqs_answer_5_sec_3") }}</p>
                            <p class="description text-secondary">{{ __("faqs_answer_5_sec_4") }}</p>
                            <p class="description text-secondary">{{ __("faqs_answer_5_sec_5") }}</p>
                            <p class="description text-secondary">{{ __("faqs_answer_5_sec_6") }}</p>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            {{ __("faqs_question_6") }}
                        </button>
                    </h2>
                    <div id="collapseSix" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <p class="description text-secondary">{{ __("faqs_answer_6_sec_1") }}</p>
                            <p class="description text-secondary">{{ __("faqs_answer_6_sec_2") }}</p>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                            {{ __("faqs_question_7") }}
                        </button>
                    </h2>
                    <div id="collapseSeven" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <p class="description text-secondary">{{ __("faqs_answer_7_sec_1") }}</p>
                            <p class="description text-secondary">{{ __("faqs_answer_7_sec_2") }}</p>
                        </div>
                    </div>
                </div>

                {{-- <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                            {{ __("faqs_question_8") }}
                        </button>
                    </h2>
                    <div id="collapseEight" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <p class="description text-secondary">{{ __("faqs_answer_8_sec_1") }}</p>
                            <p class="description text-secondary">{{ __("faqs_answer_8_sec_2") }}</p>
                        </div>
                    </div>
                </div> --}}

            </div>

        </div>
    </section>

</x-layout.home>
