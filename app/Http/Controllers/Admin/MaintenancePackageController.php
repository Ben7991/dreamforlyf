<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MaintenancePackageRequest;
use App\Models\MaintenancePackage;
use App\Models\MaintenancePackageStatus;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;

class MaintenancePackageController extends Controller
{
    public function index() {
        $totalCount = MaintenancePackage::count();
        $hiddenCount = MaintenancePackage::where('status', MaintenancePackageStatus::HIDDEN->name)->count();
        $packages = MaintenancePackage::all();

        return view("admin.maintenance-package.index", [
            "packages" => $packages,
            "total" => $totalCount,
            "hidden" => $hiddenCount
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

            return redirect("/$locale/admin/maint-packages")->with([
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

            return redirect("/$locale/admin/maint-packages")->with([
                "message" => "Updated package successfully",
                "class" => "success"
            ]);
        }
        catch(UniqueConstraintViolationException $e) {
            return redirect("/$locale/admin/ranks")->with([
                "message" => "No duplicate ranks allowed",
                "class" => "danger"
            ]);
        }
        catch(\Exception $e) {
            return redirect("/$locale/admin/ranks")->with([
                "message" => "Something wrong happened",
                "class" => "danger"
            ]);
        }
    }

    public function destroy($locale, $id) {
        try {
            $package = MaintenancePackage::findOrFail($id);

            if ($package->status === MaintenancePackageStatus::ACTIVE->name) {
                $package->status = MaintenancePackageStatus::HIDDEN->name;
            } else {
                $package->status = MaintenancePackageStatus::ACTIVE->name;
            }

            $package->save();

            return redirect()->back()->with([
                "message" => "Changed maintenance package successfully",
                "class" => "success"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->with([
                "message" => "Package doesn't exist",
                "class" => "danger"
            ]);
        }
    }
}
