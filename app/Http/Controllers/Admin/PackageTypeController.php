<?php

namespace App\Http\Controllers\Admin;

use App\Enums\EntityStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductPackageRequest;
use App\Models\PackageType;
use App\Models\Product;
use App\Models\RegistrationPackage;
use Exception;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PackageTypeController extends Controller
{
    public function index() {
        return view("admin.package-types.index", [
            "package_types" => PackageType::where("status", EntityStatus::ACTIVE->name)->get(),
            "products" => Product::all(),
            "totalTypes" => PackageType::where("status", EntityStatus::ACTIVE->name)->count()
        ]);
    }

    public function create() {
        return view("admin.package-types.create", [
            "registration_packages" => RegistrationPackage::all()
        ]);
    }

    public function store(Request $request, $locale) {
        $validated = $request->validate([
            "type" => "bail|required|max:1|regex:/^[A-Z]{1}$/",
            "package_id" => "bail|required|integer",
            "file" => "bail|required|image"
        ]);

        try {
            $path = $request->file("file")->store("public/packages");
            $packageType = new PackageType();
            $packageType->type = $validated["type"];
            $packageType->package_id = $validated["package_id"];
            $packageType->image = $path;
            $packageType->save();

            return redirect("/$locale/admin/package-types")->with([
                "message" => "Added new package type successfully",
                "class" => "success"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "message" => $e->getMessage(),
                "class" => "danger"
            ]);
        }
    }

    public function edit($locale, $id) {
        try {
            $type = PackageType::findOrFail($id);
            return view("admin.package-types.edit", [
                "type" => $type,
                "registration_packages" => RegistrationPackage::all(),
                "products" => Product::all()
            ]);
        }
        catch(UniqueConstraintViolationException $e) {
            return redirect()->back()->with([
                "message" => "Resource doesn't exist",
                "class" => "danger"
            ]);
        }
    }

    public function update(Request $request, $locale, $id) {
        $validated = $request->validate([
            "type" => "bail|required|max:1|regex:/^[A-Z]{1}$/",
            "package_id" => "bail|required|integer",
            "file" => "bail|nullable|image"
        ]);

        try {
            $packageType = PackageType::findOrFail($id);
            $packageType->type = $validated["type"];
            $packageType->package_id = $validated["package_id"];

            if (array_key_exists("file", $validated)) {
                Storage::delete($packageType->image);
                $path = $request->file("file")->store("public/packages");
                $packageType->image = $path;
            }

            $packageType->save();

            return redirect("/$locale/admin/package-types")->with([
                "message" => "Updated package type successfully",
                "class" => "success"
            ]);
        }
        catch(UniqueConstraintViolationException $e) {
            return redirect()->back()->with([
                "message" => "Resource doesn't exist",
                "class" => "danger"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "message" => "Something wrong happened",
                "class" => "danger"
            ]);
        }
    }

    public function store_product(ProductPackageRequest $request, $locale, $id) {
        try {
            $validated = $request->validated();
            $existingProduct = Product::findOrFail($validated["product_id"]);
            $existingPackage = PackageType::findOrFail($id);

            DB::table("product_package_type")->insert([
                "product_id" => $existingProduct->id,
                "type_id" => $existingPackage->id,
                "quantity" => $validated["quantity"]
            ]);

            return redirect()->back()->with([
                "message" => "Added product to package successfully",
                "class" => "success"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "message" => get_class($e),
                "class" => "danger"
            ]);
        }
    }

    public function update_product(ProductPackageRequest $request, $locale, $id) {
        try {
            $validated = $request->validated();
            $existingProduct = Product::findOrFail($validated["product_id"]);
            $existingRecord = DB::table("product_package_type")->where("id", $id)->first();

            if ($existingRecord === null) {
                throw new Exception("Product package type record doesn't exist");
            }

            DB::table("product_package_type")->where("id", $id)->update([
                "product_id" => $validated["product_id"],
                "quantity" => $validated["quantity"]
            ]);

            return redirect()->back()->with([
                "message" => "Updated record successfully successfully",
                "class" => "success"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "message" => "Resource doesn't exist",
                "class" => "danger"
            ]);
        }
    }


    public function destroy($locale, $id) {
        try {
            $packageToDelete = PackageType::findOrFail($id);
            $packageToDelete->status = EntityStatus::REMOVED->name;
            $packageToDelete->save();

            return redirect()->back()->with([
                "message" => "Package deleted successfully",
                "class" => "success"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "message" => "Resource doesn't exist",
                "class" => "danger"
            ]);
        }
    }


    public function remove_product($locale, $id) {
        try {
            DB::table("product_package_type")->delete($id);;
            return redirect()->back()->with([
                "message" => "Product removed successfully",
                "class" => "success"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "message" => "Something went wrong, please contact developer",
                "class" => "danger"
            ]);
        }
    }
}
