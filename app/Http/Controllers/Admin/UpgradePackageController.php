<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\RegistrationPackage;
use App\Models\UpgradePackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpgradePackageController extends Controller
{
    public function index() {
        $upgrades = UpgradePackage::all();
        $count = count($upgrades);
        return view("admin.upgrade-package.index", [
            "upgradePackages" => $upgrades,
            "packageCount" => $count,
            "products" => Product::all()
        ]);
    }

    public function create() {
        return view("admin.upgrade-package.create", [
            'registration_packages' => RegistrationPackage::all()
        ]);
    }

    public function store(Request $request, $locale) {
        $validated = $request->validate([
            "image" => "bail|required|image",
            "current_package" => "required|regex:/^[0-9]+$/",
            "next_package" => "required|regex:/^[0-9]+$/",
            "type" => "required"
        ]);

        $currentPackage = (int)$validated["current_package"];
        $nextPackage = (int)$validated["next_package"];

        if ($nextPackage <= $currentPackage) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Current Package should not be the same or higher than Next package"
            ]);
        }

        $existingCurrentPackage = RegistrationPackage::find($validated["current_package"]);
        $existingNextPackage = RegistrationPackage::find($validated["next_package"]);

        if ($existingCurrentPackage === null || $existingNextPackage === null) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Please make the right selection for the current package and next package"
            ]);
        }

        try {
            $imagePath = $request->file("image")->store("public/upgrade");

            UpgradePackage::create([
                "next_package_id" => $nextPackage,
                "current_package_id" => $currentPackage,
                "image" => $imagePath,
                "type" => $validated["type"]
            ]);

            return redirect("/$locale/admin/upgrade-packages")->with([
                "class" => "success",
                "message" => "Added new upgrade package successfully"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }

    public function edit(string $locale, $id) {
        try {
            $package = UpgradePackage::findOrFail($id);
            return view("admin.upgrade-package.edit", [
                "package" => $package,
                "registration_packages" => RegistrationPackage::all(),
                "products" => Product::all()
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back();
        }
    }

    public function update(Request $request, string $locale, $id) {
        $validated = $request->validate([
            "image" => "nullable|image",
            "current_package" => "required|regex:/^[0-9]+$/",
            "next_package" => "required|regex:/^[0-9]+$/",
            "type" => "required"
        ]);

        $currentPackage = (int)$validated["current_package"];
        $nextPackage = (int)$validated["next_package"];

        if ($nextPackage <= $currentPackage) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Current Package should not be the same or higher than Next package"
            ]);
        }

        $existingCurrentPackage = RegistrationPackage::find($validated["current_package"]);
        $existingNextPackage = RegistrationPackage::find($validated["next_package"]);

        if ($existingCurrentPackage === null || $existingNextPackage === null) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Please make the right selection for the current package and next package"
            ]);
        }

        try {
            $upgradePackage = UpgradePackage::findOrFail($id);
            $upgradePackage->type = $validated["type"];
            $upgradePackage->current_package_id = $currentPackage;
            $upgradePackage->next_package_id = $nextPackage;

            $imagePath = null;

            if (array_key_exists("image", $validated)) {
                Storage::delete($upgradePackage->image);

                $imagePath = $request->file("image")->store("public/upgrade");
                $upgradePackage->image = $imagePath;
            }

            $upgradePackage->save();

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Upgrade package successfully updated"
            ]);
        }
        catch(\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }


    public function store_product(Request $request, $locale, $id) {
        $validated = $request->validate([
            "product_id" => "bail|required|regex:/^[0-9]+$/",
            "quantity" => "bail|required|regex:/^[0-9]+$/"
        ]);

        $existingUpgradePackage = UpgradePackage::find($id);

        if ($existingUpgradePackage === null) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Upgrade package doesn't exist"
            ]);
        }

        $existingProduct = Product::find($validated["product_id"]);

        if ($existingProduct === null) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Product doesn't exist"
            ]);
        }

        try {
            DB::table("upgrade_package_product")->insert([
                "upgrade_package_id" => $existingUpgradePackage->id,
                "product_id" => $existingProduct->id,
                "quantity" => $validated["quantity"]
            ]);

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Successfully added new product to this package"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }


    public function update_product(Request $request, $locale, $id) {
        $validated = $request->validate([
            "product_id" => "bail|required|regex:/^[0-9]+$/",
            "quantity" => "bail|required|regex:/^[0-9]*$/"
        ]);

        $existingProduct = Product::find($validated["product_id"]);

        if (!$existingProduct) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Product doesn't exist"
            ]);
        }

        try {
            DB::table("upgrade_package_product")->where("id", $id)->update([
                "product_id" => $validated["product_id"],
                "quantity" => $validated["quantity"]
            ]);

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Update package product successfully"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }
}
