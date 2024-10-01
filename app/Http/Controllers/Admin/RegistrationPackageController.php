<?php

namespace App\Http\Controllers\Admin;

use App\Enums\EntityStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegistrationPackageRequest;
use App\Http\Requests\UpdateRegistrationPackageRequest;
use App\Models\PackageType;
use App\Models\RegistrationPackage;
use Illuminate\Database\UniqueConstraintViolationException;

class RegistrationPackageController extends Controller
{
    public function index() {
        return view("admin.registration-package.index", [
            "packages" => RegistrationPackage::all(),
            "total" => RegistrationPackage::count()
        ]);
    }

    public function create() {
        return view("admin.registration-package.create");
    }

    public function store(StoreRegistrationPackageRequest $request, $locale) {
        try {
            $validated = $request->validated();
            RegistrationPackage::create([
                "name" => $validated["name"],
                "price" => $validated["price"],
                "bv_point" => $validated["bv_point"],
                "cutoff" => $validated["cutoff"]
            ]);
            return redirect("/$locale/admin/registration-packages")->with([
                "message" => "Added new package successfully",
                "class" => "success"
            ]);
        }
        catch(\Exception $e) {
            return redirect("/$locale/admin/registration-packages")->with([
                "message" => "Something went wrong",
                "class" => "danger"
            ]);
        }
    }

    public function edit($locale, $id) {
        try {
            $registrationPackage = RegistrationPackage::findOrFail($id);
            return view("admin.registration-package.edit", [
                "package" => $registrationPackage
            ]);
        }
        catch(\Exception $e) {
            return redirect("/$locale/admin/registration-packages")->with([
                "message" => "Resource doesn't exist",
                "class" => "danger"
            ]);
        }
    }

    public function update(UpdateRegistrationPackageRequest $request, $locale, $id) {
        try {
            $validated = $request->validated();
            $package = RegistrationPackage::findOrFail($id);
            $package->name = $validated["name"];
            $package->price = $validated["price"];
            $package->bv_point = $validated["bv_point"];
            $package->cutoff = $validated["cutoff"];
            $package->save();

            return redirect("/$locale/admin/registration-packages")->with([
                "message" => "Updated registration package successfully",
                "class" => "success"
            ]);
        }
        catch(UniqueConstraintViolationException $e) {
            return redirect("/$locale/admin/registration-packages")->with([
                "message" => "No duplicate registration package names",
                "class" => "danger"
            ]);
        }
        catch(\Exception $e) {
            return redirect("/$locale/admin/registration-packages")->with([
                "message" => "Resource doesn't exist",
                "class" => "danger"
            ]);
        }
    }

    public function detail($id) {
        try {
            $packageTypes = PackageType::where("package_id", $id)
                ->where("status", EntityStatus::ACTIVE->name)
                ->get();
            $preparedResponse = [];

            foreach ($packageTypes as $packageType) {
                $path = str_replace("public", "storage", $packageType->image);
                $preparedResponse[] = [
                    "id" => $packageType->id,
                    "path" => $path,
                    "name" => $packageType->type
                ];
            }

            return response()->json([
                "data" => $preparedResponse
            ]);
        }
        catch(\Exception $e) {
            return response()->json([
                "message" => "Select only from the list of packages"
            ], 400);
        }
    }
}
