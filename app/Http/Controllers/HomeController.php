<?php

namespace App\Http\Controllers;

use App\BusinessLogic\BvCycle;
use App\BusinessLogic\CashBack;
use App\BusinessLogic\ReferralBonus;
use App\Http\Requests\SponsorRegistrationRequest;
use App\Mail\AccountRegistration;
use App\Mail\SendContactFormMail;
use App\Models\Distributor;
use App\Models\Order;
use App\Models\OrderType;
use App\Models\PackageType;
use App\Models\Portfolio;
use App\Models\Product;
use App\Models\Referral;
use App\Models\ReferralLeg;
use App\Models\RegistrationPackage;
use App\Models\Stockist;
use App\Models\Upline;
use App\Models\User;
use App\Models\UserType;
use App\Utility\PasswordGenerator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function index() {
        return view("home.index");
    }

    public function login() {
        return view("home.login");
    }

    public function forgot_password() {
        return view("home.forgot-password");
    }

    public function about_us() {
        return view("home.about-us");
    }

    public function products() {
        return view('home.product', [
            "products" => Product::orderBy("price", "asc")->paginate(8)
        ]);
    }

    public function product_details($locale, $id) {
        try {
            $product = Product::findOrFail($id);
            return view("home.product-details", [
                "product" => $product
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back();
        }
    }

    public function opportunity() {
        return view("home.opportunity", [
            "packages" => RegistrationPackage::all()
        ]);
    }

    public function faqs() {
        return view("home.faqs");
    }

    public function contact_us() {
        return view("home.contact-us");
    }

    public function send_mail(Request $request) {
        $validated = $request->validate([
            "name" => "bail|required|regex:/^[a-zA-Z ]+$/",
            "subject" => "bail|required|regex:/^[a-zA-Z ]+$/",
            "email" => "bail|required|email",
            "message" => "bail|required"
        ]);

        try {
            Mail::to("info@dreamforlyfintl.com", "DreamForLyf")->send(
                new SendContactFormMail($validated["name"], $validated["email"], $validated["subject"], $validated["message"])
            );

            return redirect()->back()->with([
                "message" => "Successfully submitted your message",
                "class" => "success"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                // "message" => "Something went wrong",
                "message" => $e->getMessage(),
                "class" => "danger"
            ]);
        }
    }

    public function sponsor(Request $request, $locale) {
        $id = $request->id;
        $token = $request->token;
        $side = $request->side;
        $sponsor = User::find($id);
        $registrationPackages = RegistrationPackage::all();

        return view("home.sponsor", [
            "id" => $id,
            "token" => $token,
            "sponsor" => $sponsor,
            "side" => $side,
            "registrationPackages" => $registrationPackages,
            "stockists" => Stockist::all()
        ]);
    }

    public function sponsor_register(SponsorRegistrationRequest $request, $locale) {
        $validated = $request->validated();
        $generatedPassword = PasswordGenerator::generate();
        $currentUser = User::find($request->id);
        $leg = $request->side === "left" ? "1st" : "2nd";
        $uplineSelectedLeg = $leg;
        $upline = $currentUser->upline;

        if ($upline === null) {
            $upline = Upline::create([
                "user_id" => $currentUser->id
            ]);
        }

        $referer = $upline;

        try {
            if (count($upline->distributors) === 2) {
                $upline = $upline->nextUpline($leg);
                $leg = $upline->nextLeg();
            }

            $portfolio = $currentUser->distributor->portfolio;
            $existingRegistrationPackage = RegistrationPackage::findOrFail($validated["package_id"]);
            $existingStockist = Stockist::find($validated["stockist_id"]);

            if ($existingStockist === null) {
                return redirect()->back()->with([
                    "class" => "danger",
                    "message" => "Stockist doesn't exist, please ensure you are selecting from the provided link"
                ]);
            }

            if ($portfolio->current_balance < $existingRegistrationPackage->price) {
                return redirect()->back()->with([
                    "class" => "danger",
                    "message" => "Upline doesn't have enough wallet"
                ]);
            }

            $existingPackageType = PackageType::findOrFail($validated["type"]);
            $purchasedProducts = DB::table("product_package_type")->where("type_id", $existingPackageType->id)->get();

            $storedDistributor = $this->storeUser($validated, $generatedPassword, $upline, $existingRegistrationPackage, $leg, $referer, $uplineSelectedLeg);
            $this->storeOrder($purchasedProducts, $storedDistributor, $existingRegistrationPackage, $existingStockist->id);
            $portfolio->subtractPurchaseAmount($existingRegistrationPackage->price);

            CashBack::giveCashBackBonus($storedDistributor, $existingRegistrationPackage);
            ReferralBonus::distributeBonus($referer, $existingRegistrationPackage);
            BvCycle::initialCycle($upline, $existingRegistrationPackage->bv_point, $storedDistributor->leg);

            Mail::to($validated["email"])->send(
                new AccountRegistration($validated["name"], $validated["email"], $generatedPassword)
            );

            return redirect("/$locale/login")->with([
                "class" => "success",
                "message" => "Please check your email for your login credentials"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong, please contact admin for assistance"
            ]);
        }
    }

    private function storeUser($data, $password, $upline, $package, $leg, $referer, $uplineSelectedLeg) {
        $storedUser = User::create([
            "id" => User::nextId(),
            "name" => $data["name"],
            "email" => $data["email"],
            "password" => $password,
            "role" => UserType::DISTRIBUTOR->name
        ]);

        $storedDistributor = Distributor::create([
            "upline_id" => $upline->id,
            "leg" => $leg,
            "registration_package_id" => $package->id,
            "country" => $data["country"],
            "city" => $data["city"],
            "user_id" => $storedUser->id,
            "phone_number" => $data["phone_number"],
            "wave" => $data["wave"],
            "next_maintenance_date" => (new Carbon())->addMonths(2)
        ]);

        Portfolio::create([
            "distributor_id" => $storedDistributor->id
        ]);

        Referral::create([
            "upline_id" => $referer->id,
            "distributor_id" => $storedDistributor->id,
            "leg" => $uplineSelectedLeg === "1st" ? ReferralLeg::LEFT->name : ReferralLeg::RIGHT->name
        ]);

        return $storedDistributor;
    }

    private function storeOrder($products, $distributor, $registrationPackage, $stockist_id) {
        $storedOrder = Order::create([
            "amount" => $registrationPackage->price,
            "distributor_id" => $distributor->id,
            "order_type" => OrderType::REGISTRATION->name,
            "stockist_id" => $stockist_id
        ]);

        foreach($products as $product) {
            DB::table("order_items")->insert([
                "order_id" => $storedOrder->id,
                "product_id" => $product->product_id,
                "quantity" => $product->quantity
            ]);

            $updatedProduct = Product::find($product->product_id);
            $updatedProduct->quantity -= $product->quantity;
            $updatedProduct->save();
        }
    }
}
