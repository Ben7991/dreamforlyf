<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\PackageType;
use App\Models\Product;
use Exception;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Psy\Readline\Hoa\Console;

class ProductController extends Controller
{
    public function index() {
        return view("admin.product.index", [
            "products" => Product::all(),
            "total" => Product::count(),
            "in_stock" => Product::where("status", "in-stock")->count(),
            "out_stock" => Product::where("status", "out-of-stock")->count(),
        ]);
    }

    public function create() {
        return view("admin.product.create");
    }

    public function store(StoreProductRequest $request, $locale) {
        try {
            $validated = $request->validated();
            $path = $request->file("image")->store("public/products");
            Product::create([
                "name" => $validated["name"],
                "quantity" => $validated["quantity"],
                "price" => $validated["price"],
                "image" => $path,
                "description_en" => $request->description_en,
                "description_fr" => $request->description_fr,
            ]);
            return redirect("/$locale/admin/products")->with([
                "message" => "Added new product successfully",
                "class" => "success"
            ]);
        }
        catch(\Exception $e) {
            return redirect("/$locale/admin/products")->with([
                "message" => "Something wrong happened",
                "class" => "danger"
            ]);
        }
    }

    public function edit($locale, $id) {
        try {
            $product = Product::findOrFail($id);
            return view("admin.product.edit", [
                "product" => $product
            ]);
        }
        catch(\Exception $e) {
            return redirect("/$locale/admin/products")->with([
                "message" => "Resource doesn't exist",
                "class" => "danger"
            ]);
        }
    }

    public function update(UpdateProductRequest $request, $locale, $id) {
        try {
            $validated = $request->validated();

            $product = Product::findOrFail($id);
            $product->name = $validated["name"];
            $product->quantity = (int)$validated["quantity"];
            $product->price = (float)$validated["price"];
            $product->status = $validated["status"];

            $product->save();

            return redirect("/$locale/admin/products")->with([
                "message" => "Updated product successfully",
                "class" => "success"
            ]);
        }
        catch(UniqueConstraintViolationException $e) {
            return redirect("/$locale/admin/products")->with([
                "message" => "Resource doesn't exist",
                "class" => "danger"
            ]);
        }
        catch(\Exception $e) {
            return redirect("/$locale/admin/products")->with([
                "message" => $e->getMessage(),
                "class" => "danger"
            ]);
        }
    }

    public function stock_status($locale, $id) {
        try {
            $product = Product::findOrFail($id);

            if ($product->status === "in-stock") {
                $product->status = "out-of-stock";
            }
            else {
                $product->status = "in-stock";
            }

            $product->save();
            return redirect("/$locale/admin/products")->with([
                "message" => "Changed product stock successfully",
                "class" => "success"
            ]);
        }
        catch(UniqueConstraintViolationException $e) {
            return redirect("/$locale/admin/products")->with([
                "message" => "Resource doesn't exist",
                "class" => "danger"
            ]);
        }
        catch(\Exception $e) {
            return redirect("/$locale/admin/products")->with([
                "message" => "Something wrong happened",
                "class" => "danger"
            ]);
        }
    }

    /**public function change_image(Request $request, $locale, $id) {
        $validated = $request->validate([
            "image" => "required|image"
        ]);

        try {

        }
        catch(Exception $e) {
            return response()->json([
                "message" => "Something went wrong"
            ], 500);
        }
    }*/
}
