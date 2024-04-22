<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MaintenancePackageRequest;
use App\Models\MaintenancePackage;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;

class MaintenancePackageController extends Controller
{
    public function index() {
        return view("admin.maintenance-package.index", [
            "packages" => MaintenancePackage::all(),
            "total" => MaintenancePackage::count()
        ]);
    }

    public function create() {
        return view("admin.maintenance-package.create");
    }

    public function store(MaintenancePackageRequest $request, $locale) {
        try {
            $validated = $request->validated();

            MaintenancePackage::create([
                "duration_in_months" => $validated["duration_in_months"],
                "total_products" => $validated["total_products"],
                "total_price" => $validated["total_price"],
                "bv_point" => $validated["bv_point"]
            ]);

            return redirect("/$locale/admin/maintenance-packages")->with([
                "message" => "Added new maintenance package successfully",
                "class" => "success"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "message" => "Something wrong happened",
                "class" => "danger"
            ]);
        }
    }

    public function edit($locale, $id) {
        try {
            $existingPackage = MaintenancePackage::findOrFail($id);
            return view("admin.maintenance-package.edit", [
                "package" => $existingPackage
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "message" => "Something wrong happened",
                "class" => "danger"
            ]);
        }
    }

    public function update(MaintenancePackageRequest $request, $locale, $id) {
        try {
            $validated = $request->validated();
            $existingPackage = MaintenancePackage::findOrFail($id);
            $existingPackage->duration_in_months = $validated["duration_in_months"];
            $existingPackage->total_products = $validated["total_products"];
            $existingPackage->total_price = $validated["total_price"];
            $existingPackage->bv_point = $validated["bv_point"];
            $existingPackage->save();

            return redirect("/$locale/admin/maintenance-packages")->with([
                "message" => "Updated package successfully",
                "class" => "success"
            ]);
        }
        catch(UniqueConstraintViolationException $e) {
            return redirect("/$this->activeLocale/admin/ranks")->with([
                "message" => "No duplicate ranks allowed",
                "class" => "danger"
            ]);
        }
        catch(\Exception $e) {
            return redirect("/$this->activeLocale/admin/ranks")->with([
                "message" => "Something wrong happened",
                "class" => "danger"
            ]);
        }
    }
}
