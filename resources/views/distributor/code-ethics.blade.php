<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DreamForLyf International</title>
    <link rel="shortcut icon" href="{{ asset("assets/img/logo-secondary.png") }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset("assets/css/admin.css") }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz,wght@6..12,300;6..12,400;6..12,600;6..12,700&display=swap" rel="stylesheet">
</head>
<body>
    <main>
        <header class="py-4">
            <div class="d-flex align-items-center justify-content-between container">
                <img src="{{ asset("assets/img/logo-secondary.png") }}" alt="DreamForLyf International Logo" width="70px">
                <x-internalize-dropdown />
            </div>
        </header>

        <section class="py-3 py-xl-4">
            <div class="container text-center">
                <h3>{{ __("code_ethics") }}</h3>
            </div>
        </section>

        <section>
            <div class="container">
                <p class="description">{{ __("ethics_top_message_1") }}</p>
                <p class="description">{{ __("ethics_top_message_2") }}</p>
                <p class="description">{{ __("ethics_top_message_3") }}</p>
                <p class="description">{{ __("ethics_top_message_4") }}</p>
            </div>
        </section>

        <section class="py-3">
            <div class="container">
                <h5 class="text-center">{{ __("ethics_heading_part_1") }}</h5>
                <p class="description">{{ __("ethics_sub_heading_part_1") }}</p>
                <p class="description">{{ __("ethics_sub_heading_part_2") }}</p>
                <p class="description">
                    {{ __("ethics_part_1_summary_start") }}<br>
                    {{ __("ethics_part_1_summary_1") }}<br>
                    {{ __("ethics_part_1_summary_2") }}<br>
                    {{ __("ethics_part_1_summary_3") }}<br>
                </p>

                <h6>{{ __("ethics_sub_part_1_i_1_description_heading") }}</h6>
                <p class="description">{{ __("ethics_sub_part_1_i_1_description_info") }}</p>
                <p class="description">
                    {{ __("ethics_sub_part_1_i_1_summary_1") }}<br>
                    {{ __("ethics_sub_part_1_i_1_summary_2") }}<br>
                    {{ __("ethics_sub_part_1_i_1_summary_3") }}<br>
                    {{ __("ethics_sub_part_1_i_1_summary_4") }}<br>
                    {{ __("ethics_sub_part_1_i_1_summary_5") }}<br>
                    {{ __("ethics_sub_part_1_i_1_summary_6") }}
                </p>

                <h6>{{ __("ethics_sub_part_1_i_2_description_heading") }}</h6>
                <p class="description">{{ __("ethics_sub_part_1_i_2_description_info") }}</p>
                <p class="description">
                    {{ __("ethics_sub_part_1_i_2_summary_1") }}<br>
                    {{ __("ethics_sub_part_1_i_2_summary_2") }}<br>
                    {{ __("ethics_sub_part_1_i_2_summary_3") }}<br>
                    {{ __("ethics_sub_part_1_i_2_summary_4") }}<br>
                    {{ __("ethics_sub_part_1_i_2_summary_5") }}<br>
                    {{ __("ethics_sub_part_1_i_2_summary_6") }}<br>
                    {{ __("ethics_sub_part_1_i_2_summary_7") }}<br>
                    {{ __("ethics_sub_part_1_i_2_summary_8") }}<br>
                    {{ __("ethics_sub_part_1_i_2_summary_9") }}<br>
                    {{ __("ethics_sub_part_1_i_2_summary_10") }}<br>
                    {{ __("ethics_sub_part_1_i_2_summary_11") }}<br>
                    {{ __("ethics_sub_part_1_i_2_summary_12") }}<br>
                    {{ __("ethics_sub_part_1_i_2_summary_13") }}<br>
                </p>
            </div>
        </section>

        <section>
            <div class="container">
                <h5 class="text-center">{{ __("ethics_heading_part_2") }}</h5>
                <p class="description">{{ __("ethics_part_2_summary_2") }}</p>
                <p class="description">{{ __("ethics_part_2_summary_2.1") }}</p>
                <p class="description">{{ __("ethics_part_2_summary_2.1_description") }}</p>
                <p class="description">{{ __("ethics_part_2_summary_2.2") }}</p>
                <p class="description">{{ __("ethics_part_2_summary_2.3") }}</p>
                <p class="description">
                    {{ __("ethics_part_2_summary_2.3_description_heading") }}<br>
                    {{ __("ethics_part_2_summary_2.3_description_info_1") }} <br>
                    {{ __("ethics_part_2_summary_2.3_description_info_2") }} <br>
                    {{ __("ethics_part_2_summary_2.3_description_info_3") }} <br>
                </p>
                <p class="description">{{ __("ethics_part_2_summary_2.4") }}</p>
                <p class="description">{{ __("ethics_part_2_summary_2.4_description_heading") }}</p>
                <p class="description">
                    {{ __("ethics_part_2_summary_2.4_description_info_1") }} <br>
                    {{ __("ethics_part_2_summary_2.4_description_info_2") }} <br>
                    {{ __("ethics_part_2_summary_2.4_description_info_3") }} <br>
                    {{ __("ethics_part_2_summary_2.4_description_info_4") }}
                </p>
                <p class="description">{{ __("ethics_part_2_summary_2.5") }}</p>
                <p class="description">{{ __("ethics_part_2_summary_2.5_description") }}</p>
                <p class="description">{{ __("ethics_part_2_summary_2.6") }}</p>
                <p class="description">{{ __("ethics_part_2_summary_2.6_description") }}</p>
            </div>
        </section>
    </main>

    <footer class="pt-3 pb-5">
        <div class="text-center">
            <form action="/{{ App::currentLocale() }}/distributor/code-ethics" method="POST">
                @csrf

                <button class="btn btn-success">
                    <i class="bi bi-check2"></i> {{ __("agreed") }}
                </button>
            </form>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
