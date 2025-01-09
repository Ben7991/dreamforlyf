<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\MaintenancePackageController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RankController;
use App\Http\Controllers\Admin\RegistrationPackageController;
use App\Http\Controllers\Admin\PackageTypeController;
use App\Http\Controllers\Admin\DistributorController as AdminDistributorController;
use App\Http\Controllers\Admin\StockistController as AdminStockistController;
use App\Http\Controllers\Admin\UpgradePackageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Distributor\DistributorController;
use App\Http\Controllers\Distributor\MyTreeController;
use App\Http\Controllers\Distributor\OrderHistoryController;
use App\Http\Controllers\Distributor\PortfolioController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Distributor\MaintenancePackageController as DistributorMainPackController;
use App\Http\Controllers\Stockist\StockistController;

/**
 * general user routes
 */
Route::get('/', function () {
    $currentLocale = App::currentLocale();
    return redirect("$currentLocale");
});

// Route::get("/ad-create-us-dats", [AdminController::class, "set_admin"]);

Route::get("/users/{id}", [AuthController::class, "user"]);
Route::get("/registration-packages/{id}/detail", [RegistrationPackageController::class, "detail"]);
Route::get("/users/{email}/check", [AuthController::class, "check"]);
Route::get("/products/{id}", [DistributorMainPackController::class, "product_details"])->middleware(["auth", "user.distributor"]);
Route::post("/maint-packages", [DistributorMainPackController::class, "store"])->middleware(["auth", "user.distributor"]);

Route::prefix("{locale}")->group(function () {
    Route::middleware("internationalize")->group(function () {
        // logout feature
        Route::post("logout", [AuthController::class, "logout"])->middleware("auth");

        Route::get("downline/{id}/detail", [MyTreeController::class, "downline_detail"])->middleware(["auth", "user.distributor"]);
        Route::get("users/{userId}/detail", [MyTreeController::class, "user_detail"])->middleware(["auth", "user.distributor"]);
    });
});

Route::get("admin/analytics-data", [AnalyticsController::class, "fetch_data"]);

Route::prefix("{locale}")->group(function () {
    Route::middleware(['internationalize'])->group(function () {
        Route::get("", [HomeController::class, "index"]);
        Route::get("about-us", [HomeController::class, "about_us"]);
        Route::get("opportunity", [HomeController::class, "opportunity"]);
        Route::get("faqs", [HomeController::class, "faqs"]);
        Route::get("contact-us", [HomeController::class, "contact_us"]);
        Route::post("contact-us", [HomeController::class, "send_mail"]);

        Route::get("products", [HomeController::class, "products"]);
        Route::get("products/{id}/details", [HomeController::class, "product_details"]);

        Route::get("login", [HomeController::class, "login"])->name("login");
        Route::post("login", [AuthController::class, "login"]);

        Route::get("forgot-password", [HomeController::class, "forgot_password"]);
        Route::post("forgot-password", [AuthController::class, "forgot_password"]);

        Route::get("reset-password", [AuthController::class, "reset_password"])->middleware("reset.password");
        Route::post("reset-password", [AuthController::class, "change_password"])->middleware("reset.password");

        Route::get("sponsor", [HomeController::class, "sponsor"])->middleware("sponsor.valid");
        Route::post("sponsor/register", [HomeController::class, "sponsor_register"])->middleware("sponsor.valid");
    });
});


/**
 * all admin routes
 */
Route::post("/profile/image-change", [AuthController::class, "image_change"])->middleware("auth");
Route::post("/admin/analytics-data", [AnalyticsController::class, "fetch_data"])->middleware("auth", "user.admin");
Route::prefix("{locale}/admin")->group(function () {
    Route::middleware(["internationalize", "auth", "user.admin"])->group(function () {
        Route::get("", [AdminController::class, "index"]);
        Route::get("announce", [AdminController::class, "announcement"]);
        Route::post("announce", [AdminController::class, "store_announcement"]);
        Route::delete("announce/{id}", [AdminController::class, "remove_announcement"]);


        Route::prefix("analytics")->group(function () {
            Route::get("", [AnalyticsController::class, "index"]);
            Route::get("personal-purchase", [AnalyticsController::class, "personal_purchase"]);
            Route::get("upgrade-bonus", [AnalyticsController::class, "upgrade_bonus"]);
            Route::get("maint", [AnalyticsController::class, "maintenance"]);
            Route::get("general-assessment", [AnalyticsController::class, "general_assessment"]);
        });

        Route::get("profile", [AdminController::class, "profile"]);
        Route::post("profile/personal-information", [AuthController::class, "personal_information"]);
        Route::post("profile/password-change", [AuthController::class, "password_change"]);

        Route::get("qualified-ranks", [AdminController::class, "qualified_ranks"]);
        Route::get("qualified-ranks/{id}", [AdminController::class, "qualified_rank_details"]);
        Route::post("qualified-ranks/{id}/award", [AdminController::class, "award_qualified_rank"]);

        Route::get("qualified-pool", [AdminController::class, "qualified_pool"]);
        Route::get("qualified-pool/{id}", [AdminController::class, "qualified_pool_details"]);
        Route::put("qualified-pool/{id}/award", [AdminController::class, "award_qualified_pool"]);

        Route::get("leadership-bonus", [AdminDistributorController::class, "leadership_bonus"]);
        Route::post("leadership-bonus/{id}/pay", [AdminDistributorController::class, "pay_leadership_bonus"]);
        Route::post("leadership-bonus/pay-all", [AdminDistributorController::class, "pay_all_leadership_bonus"]);

        Route::put("products/{id}/stock-status", [ProductController::class, "stock_status"]);
        Route::resource("products", ProductController::class)->except([
            "destroy",
            "show"
        ]);

        Route::resource("ranks", RankController::class)->except([
            "destroy",
            "show"
        ]);

        Route::resource("registration-packages", RegistrationPackageController::class)->except([
            "destroy",
            "show"
        ]);

        Route::post("upgrade-packages/{id}/product", [UpgradePackageController::class, "store_product"]);
        Route::put("upgrade-packages/{id}/product", [UpgradePackageController::class, "update_product"]);
        Route::delete("upgrade-packages/{id}/product", [UpgradePackageController::class, "remove_product"]);
        Route::resource("upgrade-packages", UpgradePackageController::class)->except(["show"]);

        Route::post("package-types/{id}/product", [PackageTypeController::class, "store_product"]);
        Route::put("package-types/{id}/product", [PackageTypeController::class, "update_product"]);
        Route::delete("package-types/{id}/product", [PackageTypeController::class, "remove_product"]);
        Route::resource("package-types", PackageTypeController::class)->except(["show"]);

        Route::resource('order-history', OrderController::class)->only([
            "index",
            "show",
            "update"
        ]);

        Route::put("distributors/{id}/reset-withdrawal-pin", [AdminDistributorController::class, "reset_withdrawal_pin"]);
        Route::post("distributors/bv-reset/dollar", [AdminDistributorController::class, "bv_reset"]);
        Route::post("distributors/{id}/wallet", [AdminDistributorController::class, "wallet"]);
        Route::put("distributors/{id}/reverse-transfer", [AdminDistributorController::class, "reverse_transfer"]);
        Route::get("distributors/wallet-transfer", [AdminDistributorController::class, "wallet_transfer"]);
        Route::resource("distributors", AdminDistributorController::class)->except(["destroy"]);

        Route::get("bonus-withdrawals", [AdminDistributorController::class, "bonus_withdrawals"]);
        Route::put("bonus-withdrawals/{id}/approve", [AdminDistributorController::class, "approve_withdrawal"]);
        Route::get("bonus-withdrawals/filter", [AdminDistributorController::class, "filter_withdrawals"]);
        Route::get("bonus-withdrawals/{id}", [AdminDistributorController::class, "withdrawal_details"]);

        Route::get("stockist-withdrawals", [AdminStockistController::class, "stockist_withdrawals"]);
        Route::get("stockist-withdrawals/request", [AdminStockistController::class, "stockist_withdrawal_requests"]);
        Route::put("stockist-withdrawals/request/{id}", [AdminStockistController::class, "approve_request"]);
        Route::get("stockist-withdrawals/{id}", [AdminStockistController::class, "withdrawal_details"]);
        Route::put("stockist-withdrawals/{id}/approve", [AdminStockistController::class, "withdrawal_approve"]);

        Route::get("upgrade-history", [AdminController::class, "upgrade_history"]);

        Route::post("stockists/{id}/transfer-wallet", [AdminStockistController::class, "transfer_wallet"]);
        Route::get("stockists/transfer", [AdminStockistController::class, "transfer"]);
        Route::put("stockists/{id}/reverse-transfer", [AdminStockistController::class, "reverse_transfer"]);
        Route::put('stockists/{id}/suspend', [AdminStockistController::class, 'suspend_account']);
        Route::resource('stockists', AdminStockistController::class)->except([
            "delete",
        ]);
    });
});


/**
 * all distributors routes
 */
Route::get("/distributor/{value}/credential", [DistributorController::class, "check_credential"])->middleware(["auth", "user.distributor"]);
Route::prefix("{locale}/distributor")->group(function () {
    Route::middleware(["internationalize", "auth", "user.distributor", "code.ethics"])->group(function () {
        Route::get("", [DistributorController::class, "index"]);
        Route::withoutMiddleware("code.ethics")->group(function () {
            Route::get("code-ethics", [DistributorController::class, "code_ethics"]);
            Route::post("code-ethics", [DistributorController::class, "read_code_ethics"]);
        });

        Route::get("ethics", [DistributorController::class, "ethics"]);
        Route::get("referred-distributors", [DistributorController::class, "referred_distributors"]);

        Route::prefix("profile")->group(function () {
            Route::get("", [DistributorController::class, "profile"]);
            Route::post("personal-information", [AuthController::class, "personal_information"]);
            Route::post("password-change", [AuthController::class, "password_change"]);
            Route::post("set-pin", [DistributorController::class, "set_pin"]);
            Route::post("change-pin", [DistributorController::class, "change_pin"]);
            Route::post("bank", [DistributorController::class, "store_bank_details"]);
        });

        Route::get("products", [DistributorController::class, "products"]);
        Route::get("products/{id}/details", [DistributorController::class, "product_details"]);
        Route::post("products/{id}/purchase", [DistributorController::class, "product_purchase"]);

        Route::get("ranks", [DistributorController::class, "ranks"]);

        Route::get("membership-packages", [DistributorController::class, "membership_packages"]);
        Route::post("membership-packages/upgrade", [DistributorController::class, "upgrade_package"]);
        Route::get("membership-packages/upgrade/products", [DistributorController::class, "upgrade_product_selection"])->name("upgrade.selected");
        Route::post("membership-packages/upgrade/{id}/products", [DistributorController::class, "complete_upgrade"]);

        Route::get("package-types", [DistributorController::class, "package_types"]);

        Route::get("order-history", [OrderHistoryController::class, "index"]);
        Route::get("order-history/{id}/show", [OrderHistoryController::class, "show"]);

        Route::get("my-tree", [MyTreeController::class, "index"]);
        Route::get("my-tree/create", [MyTreeController::class, "create"]);
        Route::post("my-tree/register", [MyTreeController::class, "register"]);
        Route::get("my-tree/{id}", [MyTreeController::class, "downline_tree"]);

        Route::get("qualified-ranks", [DistributorController::class, "qualified_ranks"]);
        Route::get("qualified-pool", [DistributorController::class, "qualified_pool"]);

        Route::get("portfolios", [PortfolioController::class, "index"]);
        Route::get("transaction-history", [PortfolioController::class, "transaction_history"]);
        Route::get("upgrade-history", [DistributorController::class, "upgrade_history"]);
        Route::get("bonus-withdrawal", [PortfolioController::class, "bonus_withdrawal"]);
        Route::post("bonus-withdrawal/request", [PortfolioController::class, "withdrawal_request"])->middleware("withdrawal.day");

        Route::get("complan", [DistributorController::class, "complan"]);
    });
});


/**
 * all stockist routes
 */
Route::prefix("{locale}/stockist")->group(function () {
    Route::middleware(["auth", "user.stockist", "internationalize"])->group(function () {
        Route::get("", [StockistController::class, "index"]);

        Route::prefix("profile")->group(function () {
            Route::get("", [StockistController::class, "profile"]);
            Route::post("personal-information", [StockistController::class, "personal_information"]);
            Route::post("password-change", [AuthController::class, "password_change"]);
            Route::post("bank", [StockistController::class, "set_bank_details"]);
        });

        Route::get("order-history", [StockistController::class, "orderHistory"]);
        Route::get("order-history/{id}", [StockistController::class, "orderDetails"]);
        Route::put("order-history/{id}", [StockistController::class, "changeOrderStatus"]);

        Route::get("transfer-wallet", [StockistController::class, "transferWallet"]);
        Route::put("transfer-wallet/{id}", [StockistController::class, "sendDistributorWallet"]);

        Route::get("bonus-withdrawal", [StockistController::class, "bonus_withdrawal"]);
        Route::post("bonus-withdrawal/request", [StockistController::class, "request_withdrawal"]);
        Route::post("bonus-withdrawal/make-withdrawal", [StockistController::class, "make_withdrawal"]);
    });
});
