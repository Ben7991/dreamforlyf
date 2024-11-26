<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RankRequest;
use App\Models\Rank;
use Illuminate\Database\UniqueConstraintViolationException;

class RankController extends Controller
{
    public function index() {
        return view("admin.rank.index", [
            "ranks" => Rank::orderBy("bv_point", "asc")->get(),
            "total" => Rank::count()
        ]);
    }

    public function create() {
        return view("admin.rank.create");
    }

    public function store(RankRequest $request) {
        try {
            $validated = $request->validated();
            Rank::create([
                "name" => $validated["name"],
                "bv_point" => $validated["bv_point"],
                "award" => $validated["award"]
            ]);

            return redirect()->back()->with([
                "message" => "Added new rank successfully",
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
            $rank = Rank::findOrFail($id);
            return view("admin.rank.edit", [
                "rank" => $rank
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "message" => "Resource doesn't exist",
                "class" => "danger"
            ]);
        }
    }

    public function update(RankRequest $request, $locale, $id) {
        try {
            $rank = Rank::findOrFail($id);
            $validated = $request->validated();
            $rank->name = $validated["name"];
            $rank->bv_point = $validated["bv_point"];
            $rank->award = $validated["award"];
            $rank->save();

            return redirect("/$locale/admin/ranks")->with([
                "message" => "Updated rank successfully",
                "class" => "success"
            ]);
        }
        catch(UniqueConstraintViolationException $e) {
            return redirect()->back()->with([
                "message" => "No duplicate ranks allowed",
                "class" => "danger"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "message" => "Resource doesn't exist",
                "class" => "danger"
            ]);
        }
    }
}
