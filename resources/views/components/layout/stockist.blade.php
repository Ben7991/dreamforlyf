<!DOCTYPE html>
<html lang="{{ App::currentLocale() }}">
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
</head>
<body>
    <main class="main">
        <header class="main-header shadow-sm">
            <div class="container-fluid d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <button class="main-hamburger me-2 rounded">
                        <i class="bi bi-list fs-6"></i>
                    </button>
                    <img src="{{ asset("assets/img/logo-secondary.png") }}" alt="DreamForLyf Logo" class="main-logo">
                </div>
                <div class="d-flex align-items-center">
                    <div class="dropdown me-2">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="bi bi-person fs-6"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="/{{ App::currentLocale() }}/stockist/profile">
                                    <i class="bi bi-person"></i> {{ __("profile") }}
                                </a>
                            </li>
                            <li>
                                <form action="/{{ App::getLocale() }}/logout" method="POST">
                                    @csrf
                                    <button class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> {{ __("logout") }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <x-internalize-dropdown />
                </div>
            </div>
        </header>

        <section class="content">
            <x-alert />

            <div class="backdrop"></div>
            <aside class="content-drawer">
                <div class="py-1 mb-1">
                    <div class="drawer-img d-flex align-items-center justify-content-center {{ Auth::user()->image === null ? '' : 'd-none' }} profile-placeholder">
                        <i class="bi bi-person display-4"></i>
                    </div>
                    <img src="{{ asset(Str::replaceFirst('public', 'storage', Auth::user()->image)) }}" alt="User Image"
                        class="drawer-img d-block {{ Auth::user()->image === null ? 'd-none' : '' }} profile-image">
                    <h6 class="my-3 drawer-heading">{{ Auth::user()->name }}</h6>
                </div>
                <div class="drawer-nav">
                    <a href="/{{ App::currentLocale() }}/stockist" class="drawer-link rounded {{ $title == "Dashboard" ? 'active' : '' }}">
                        <i class="bi bi-bar-chart"></i> {{  __("dashboard") }}
                    </a>
                    <hr>
                    <a href="/{{ App::currentLocale() }}/stockist/order-history" class="drawer-link rounded d-flex align-items-center justify-content-between {{ $title == "Order History" ? 'active' : '' }}">
                        <span>
                            <i class="bi bi-clock-history"></i> {{  __("order_history") }}
                        </span>
                        <span class="drawer-link-number">{{ session()->get("order_count") }}</span>
                    </a>
                    <a href="/{{ App::currentLocale() }}/stockist/transfer-wallet" class="drawer-link rounded {{ $title == "Wallet" ? 'active' : '' }}">
                        <i class="bi bi-wallet"></i> {{  __("transfer_wallet") }}
                    </a>
                    <a href="/{{ App::currentLocale() }}/stockist/bonus-withdrawal" class="drawer-link rounded {{ $title == "Bonus Withdrawals" ? 'active' : '' }}">
                        <i class="bi bi-box-arrow-up"></i> {{  __("bonus_withdrawal") }}
                    </a>
                </div>
            </aside>
            <section class="content-main">
                @if (session()->get("isWithdrawalDay") === true)
                    <div class="alert alert-success">
                        <p class="mt-1 mb-0 fs-5"><marquee class="m-0">{{ __("withdrawal_notice") }}</marquee></p>
                    </div>
                @endif

                {{ $slot }}

                <footer class="p-3 bg-light rounded mt-5">
                    <div class="container-fluid p-0 text-center d-md-flex justify-content-between">
                        <p class="m-md-0">&copy; {{ __("footer_copyright") }}</p>
                        <a href="/{{ App::currentLocale() }}/distributor/ethics">Code of Ethics <i class="bi bi-arrow-right"></i></a>
                    </div>
                </footer>
            </section>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset("assets/js/admin/main.js") }}"></script>
    <script src="{{ asset("assets/js/general.js") }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    @stack("scripts")
</body>
</html>
