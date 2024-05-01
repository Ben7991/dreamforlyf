<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\BonusWithdrawal;
use App\Models\Order;
use App\Models\PoolBonus;
use App\Models\PoolBonusStatus;
use App\Models\Rank;
use App\Models\UpgradeHistory;
use App\Models\Upline;
use App\Models\User;
use App\Models\UserType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index() {
        $bonusWithdrawals = BonusWithdrawal::where("status", "PENDING")->take(5)->get();
        $pendingOrderCount = Order::where("status", "PENDING")->count();
        $qualifiedPoolCount = 0;
        $qualifiedRankCount = 0;
        $awardCount = DB::table("upline_ranks")->count();

        return view("admin.index", [
            "pendingOrderCount" => $pendingOrderCount,
            "qualifiedPoolCount" => $qualifiedPoolCount,
            "qualifiedRankCount" => $qualifiedRankCount,
            "bonusWithdrawals" => $bonusWithdrawals,
            "upgrades" => UpgradeHistory::orderBy("id", "desc")->take(5)->get(),
            "awardCount" => $awardCount
        ]);
    }

    public function announcement() {
        $currentDate = Carbon::now()->toDateString();
        Announcement::where("end_date", "<", $currentDate)->delete();

        $createdAnnouncement = Announcement::first();
        $startDate = $endDate = "";

        if($createdAnnouncement !== null) {
            $startDate = Carbon::parse($createdAnnouncement->start_date)->toFormattedDayDateString();
            $endDate = Carbon::parse($createdAnnouncement->end_date)->toFormattedDayDateString();
        }

        return view("admin.announcement", [
            "announcement" => $createdAnnouncement,
            "start_date" => $startDate,
            "end_date" => $endDate,
        ]);
    }

    public function store_announcement(Request $request, $locale) {
        $validated = $request->validate([
            "description_en" => "required",
            "description_fr" => "required",
            "end_date" => "required|date"
        ]);

        try {
            Announcement::create([
                "start_date" => Carbon::now()->toDateString(),
                "description_en" => $validated["description_en"],
                "description_fr" => $validated["description_fr"],
                "end_date" => $validated["end_date"]
            ]);

            return redirect()->back()->with([
                "message" => "Announcement created successfully",
                "class" => "success"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "message" => "Something went wrong",
                "class" => "danger"
            ]);
        }
    }

    public function remove_announcement($locale, $id) {
        try {
            Announcement::where("id", $id)->delete();
            return redirect()->back()->with([
                "message" => "Announcement deleted successfully",
                "class" => "success"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "message" => "Something went wrong",
                "class" => "danger"
            ]);
        }
    }

    public function qualified_ranks() {
        $qualifiedRanks = DB::table("upline_ranks")->get();
        $qualified = [];
        $awarded = 0;
        $pending = 0;
        $total = count($qualifiedRanks);

        foreach($qualifiedRanks as $rank) {
            if ($rank->status === "PENDING") {
                $pending++;
            }
            else {
                $awarded++;
            }
            $qualified[] = [
                "id" => $rank->id,
                "rank" => Rank::find($rank->rank_id),
                "status" => $rank->status,
                "distributor" => Upline::find($rank->upline_id)->user->name,
                "distributor_id" => Upline::find($rank->upline_id)->user->id,
                "date_added" => $rank->date_added
            ];
        }

        return view("admin.qualified-rank.index", [
            "total" => $total,
            "qualified" => $qualified,
            "awarded" => $awarded,
            "pending" => $pending
        ]);
    }

    public function qualified_rank_details($locale, $id) {
        try {
            $fetchedRecord = DB::table("upline_ranks")->find($id);
            $qualifiedRank = Rank::findOrFail($fetchedRecord->rank_id);
            $user = Upline::findOrFail($fetchedRecord->upline_id)->user;

            return view("admin.qualified-rank.show", [
                "rank" => $qualifiedRank,
                "user" => $user,
                "date_time" => $fetchedRecord->date_added,
                "record_id" => $fetchedRecord->id,
                "status" => $fetchedRecord->status
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Resource not found"
            ]);
        }
    }

    public function award_qualified_rank($locale, $id) {
        try {
            $qualified = DB::table("upline_ranks")->where("id", $id)->first();

            if ($qualified === null) {
                return redirect()->back()->with([
                    "class" => "danger",
                    "message" => "Resource doesn't exist"
                ]);
            }

            DB::table("upline_ranks")->where("id", $id)->update([
                "status" => "AWARDED"
            ]);

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Awarded distributor successfully"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }

    public function qualified_pool() {
        return view("admin.qualified-pool.index", [
            "poolRecords" => PoolBonus::all(),
            "total" => PoolBonus::count(),
            "pending" => PoolBonus::where("status", PoolBonusStatus::PENDING->name)->count(),
            "awarded" => PoolBonus::where("status", PoolBonusStatus::AWARDED->name)->count(),
        ]);
    }

    public function qualified_pool_details($locale, $id) {
        try {
            $poolRecord = PoolBonus::findOrFail($id);

            return view("admin.qualified-pool.show", [
                "poolRecord" => $poolRecord
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }

    public function award_qualified_pool($locale, $id) {
        try {
            $poolRecord = PoolBonus::findOrFail($id);
            $poolRecord->status = PoolBonusStatus::AWARDED->name;
            $poolRecord->save();

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Awarded pool successfully"
            ]);
        }
        catch(\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong"
            ]);
        }
    }

    public function profile() {
        return view("admin.profile");
    }

    public function upgrade_history() {
        $upgrades = UpgradeHistory::all();

        return view("admin.upgrade-history", [
            "upgrades" => $upgrades,
            "total" => count($upgrades)
        ]);
    }

    public function set_admin() {
        User::create([
            'id' => "AMDFSLD344",
            'name' => "James Smith",
            'email' => "datssosh@gmail.com",
            'password' => "sldfjslfj34**&&",
            "role" => UserType::ADMIN->name
        ]);

        return "User created";
    }
}
