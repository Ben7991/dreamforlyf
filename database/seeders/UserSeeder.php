<?php

namespace Database\Seeders;

use App\Models\Distributor;
use App\Models\Portfolio;
use App\Models\Stockist;
use App\Models\Upline;
use App\Models\User;
use App\Models\UserType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "id" => User::nextId(),
            "name" => "Lydakra Jnr",
            "email" => "lydakra123@gmail.com",
            "password" => "James1234",
            "role" => UserType::ADMIN->name
        ]);

        // ------------ Network Marketing Logic --------------------------

        $master = User::create([
            "id" => User::nextId(),
            "name" => "Master User",
            "email" => "master@dream.com",
            "password" => "#3434@*(*!@()!@",
            "role" => UserType::ADMIN->name
        ]);
        $master->status = "not-active";
        $master->save();

        $masterUpline = Upline::create([ "user_id" => $master->id ]);

        $companyUser = User::create([
            "id" => User::nextId(),
            "name" => "Dream For Lyf",
            "email" => "company@dream.com",
            "password" => "#3434@*(*!@()!@",
            "role" => UserType::DISTRIBUTOR->name,
            "status" => "not-active"
        ]);
        $companyUser->save();

        $rootDistributor = Distributor::create([
            "upline_id" => $masterUpline->id,
            "leg" => "1st",
            "registration_package_id" => 1,
            "country" => "DreamForLyf",
            "city" => "DreamForLyf",
            "user_id" => $companyUser->id,
            "phone_number" => "0454534343423",
            "wave" => "0454534343423",
            "next_maintenance_date" => (new Carbon())->addMonths(2)
        ]);

        Upline::create([ "user_id" => $companyUser->id ]);

        Portfolio::create([
            "distributor_id" => $rootDistributor->id,
            "current_balance" => 1000000,
            "commission_wallet" => 1000000,
        ]);

        // ----------- End Network Marketing Logic ------------------------


        // ----------- Stockist ----------------------------------
        // name, email, password, role, country, code

        $stockistUser = User::create([
            "id" => User::nextId(),
            'name' => "Dream For Lyf",
            'email' => "dreamforlyf@help.com",
            'password' => "&^%%%&^%*(&()(&*(%^%^&&%^%$&$$^$*&^^*&^&%(&^^*)*()*&)*(&)&*(&^&*%^&^%$%^$$%$#$%$@#@%$#&%^$^%^&%^*%$#%#%#$#$",
            "role" => UserType::STOCKIST->name
        ]);

        Stockist::create([
            "country" => "Ivory Coast",
            "city" => "Head Office",
            "code" => "DreamForLyf Head Office",
            "user_id" => $stockistUser->id
        ]);

        // ----------- end stockist -------------------------------
    }
}
