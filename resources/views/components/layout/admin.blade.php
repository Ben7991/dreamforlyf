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
                                <a class="dropdown-item" href="/{{ App::currentLocale() }}/admin/profile">
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
                    <a href="/{{ App::currentLocale() }}/admin" class="drawer-link rounded {{ $title == "Dashboard" ? 'active' : '' }}">
                        <i class="bi bi-bar-chart"></i> {{  __("dashboard") }}
                    </a>
                    <a href="/{{ App::currentLocale() }}/admin/announce" class="drawer-link rounded {{ $title == "Announcement" ? 'active' : '' }}">
                        <i class="bi bi-megaphone"></i> {{  __("announcement") }}
                    </a>
                    <a href="/{{ App::currentLocale() }}/admin/products" class="drawer-link rounded d-flex align-items-center justify-content-between {{ $title == "Products" ? 'active' : '' }}">
                        <span>
                            <i class="bi bi-capsule"></i> {{  __("products") }}
                        </span>
                        <span class="drawer-link-number">{{ session()->get("product_count") }}</span>
                    </a>
                    <a href="/{{ App::currentLocale() }}/admin/ranks" class="drawer-link rounded {{ $title == "Ranks" ? 'active' : '' }}">
                        <i class="bi bi-ladder"></i> {{  __("ranks") }}
                    </a>
                    <hr>

                    <a href="/{{ App::currentLocale() }}/admin/registration-packages" class="drawer-link rounded {{ $title == "Registration Packages" ? 'active' : '' }}">
                        <i class="bi bi-gift"></i> {{  __("registration_packages") }}
                    </a>
                    <a href="/{{ App::currentLocale() }}/admin/package-types" class="drawer-link rounded {{ $title == "Registration Package Types" ? 'active' : '' }}">
                        <i class="bi bi-collection"></i> {{  __("registration_package_type") }}
                    </a>
                    <a href="/{{ App::currentLocale() }}/admin/upgrade-packages" class="drawer-link rounded {{ $title == "Upgrade Packages" ? 'active' : '' }}">
                        <i class="bi bi-graph-up-arrow"></i> {{  __("upgrade_packages") }}
                    </a>
                    <a href="/{{ App::currentLocale() }}/admin/maint-packages" class="drawer-link rounded {{ $title == "Maintenance Packages" ? 'active' : '' }}">
                        <i class="bi bi-gear"></i> {{  __("maintenance_packages") }}
                    </a>
                    <hr>

                    <a href="/{{ App::currentLocale() }}/admin/order-history" class="drawer-link rounded d-flex align-items-center justify-content-between {{ $title == "Order History" ? 'active' : '' }}">
                        <span>
                            <i class="bi bi-clock-history"></i> {{  __("order_history") }}
                        </span>
                        <span class="drawer-link-number">{{ session()->get("order_count") }}</span>
                    </a>
                    <a href="/{{ App::currentLocale() }}/admin/bonus-withdrawals" class="drawer-link rounded d-flex align-items-center justify-content-between {{ $title == "Bonus Withdrawals" ? 'active' : '' }}">
                        <span>
                            <i class="bi bi-cash-stack"></i> {{ __("bonus_withdrawal") }}
                        </span>
                        <span class="drawer-link-number">{{ session()->get("withdrawal_count") }}</span>
                    </a>
                    <a href="/{{ App::currentLocale() }}/admin/upgrade-history" class="drawer-link rounded {{ $title == "Upgrade History" ? 'active' : '' }}">
                        <i class="bi bi-graph-up-arrow"></i> {{  __("upgrade_history") }}
                    </a>
                    <hr>


                    <a href="/{{ App::currentLocale() }}/admin/qualified-ranks" class="drawer-link rounded d-flex align-items-center justify-content-between {{ $title == "Qualified Ranks" ? 'active' : '' }}">
                        <span>
                            <i class="bi bi-check2-circle"></i> {{  __("qualified_ranks") }}
                        </span>
                        <span class="drawer-link-number">{{ session()->get("qualified_rank_count") }}</span>
                    </a>
                    <a href="/{{ App::currentLocale() }}/admin/qualified-pool" class="drawer-link rounded d-flex align-items-center justify-content-between {{ $title == "Qualified Pool" ? 'active' : '' }}">
                        <span>
                            <i class="bi bi-database-check"></i> {{  __("qualified_pool") }}
                        </span>
                        <span class="drawer-link-number">{{ session()->get("qualified_pool_count") }}</span>
                    </a>
                    <a href="/{{ App::currentLocale() }}/admin/leadership-bonus" class="drawer-link rounded d-flex align-items-center justify-content-between {{ $title == "Leadership Bonus" ? 'active' : '' }}">
                        <span>
                            <i class="bi bi-patch-check"></i> {{  __("leadership_bonus") }}
                        </span>
                        <span class="drawer-link-number">{{ session()->get("leadership_count") }}</span>
                    </a>
                    <hr>

                    <a href="/{{ App::currentLocale() }}/admin/distributors" class="drawer-link rounded {{ $title == "Distributors" ? 'active' : '' }}">
                        <i class="bi bi-people"></i> {{ __("distributors") }}
                    </a>
                    <a href="/{{ App::currentLocale() }}/admin/stockists" class="drawer-link rounded {{ $title == "Stockists" ? 'active' : '' }}">
                        <i class="bi bi-person-gear"></i> {{ __("stockists") }}
                    </a>
                    <hr>

                    <a href="/{{ App::currentLocale() }}/admin/analytics" class="drawer-link rounded {{ $title == "Analytics" ? 'active' : '' }}">
                        <i class="bi bi-pie-chart-fill"></i> {{ __("analytics") }}
                    </a>
                </div>
            </aside>
            <section class="content-main">
                {{ $slot }}
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
