<?php

namespace App\Http\Controllers;

use App\Mail\ResetPassword;
use App\Models\CodeEthics;
use App\Models\User;
use App\Models\UserType;
use App\Utility\PasswordGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function login(Request $request, $locale)
    {
        $validated = $request->validate([
            "email" => "bail|required|email",
            "password" => "bail|required"
        ]);

        try {
            if (Auth::attempt($validated)) {
                if (Auth::user()->status === 'suspend') {
                    Auth::logout();

                    return redirect()->back()->with([
                        'class' => 'danger',
                        'message' => 'Your account has been suspended, please contact admin for assistance'
                    ]);
                }

                $request->session()->regenerate();

                if (Auth::user()->role === 'ADMIN') {
                    return redirect()->intended("/$locale/admin")->with([
                        'class' => 'success',
                        'message' => 'You have logged-in successfully'
                    ]);
                } else if (Auth::user()->role === 'DISTRIBUTOR') {
                    $path = "/$locale/distributor";

                    if (Auth::user()->distributor->code_ethics === CodeEthics::PENDING->name) {
                        $path = "/$locale/distributor/code-ethics";
                    }

                    return redirect()->intended($path)->with([
                        'class' => 'success',
                        'message' => 'You have logged-in successfully'
                    ]);
                } else if (Auth::user()->role === 'STOCKIST') {
                    return redirect()->intended("/$locale/stockist")->with([
                        'class' => 'success',
                        'message' => 'You have logged-in successfully'
                    ]);
                }
            } else {
                return redirect()->back()->with([
                    "message" => "Invalid email address and or password",
                    "class" => "danger"
                ]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with([
                "message" => "Something wrong happened",
                "class" => "danger"
            ]);
        }
    }

    public function forgot_password(Request $request, $locale)
    {
        $validated = $request->validate([
            "email" => "bail|required|email"
        ]);

        try {
            $generatedPassword = PasswordGenerator::generate();
            $token = Hash::make($generatedPassword);
            $existingUser = User::where("email", $validated["email"])->first();

            if ($existingUser === null) {
                return redirect()->back()->with([
                    "class" => "danger",
                    "message" => "Email address not recognized in our system"
                ]);
            }

            DB::table("password_reset_tokens")->where("email", $validated["email"])->delete();
            DB::table("password_reset_tokens")->insert([
                "email" => $existingUser->email,
                "token" => $token
            ]);

            Mail::to($existingUser->email)->send(
                new ResetPassword($existingUser->name, $existingUser->email, $token)
            );

            return redirect("/$locale/login")->with([
                "class" => "success",
                "message" => "Please check your email for reset credentials"
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something wrong happened, please ensure your email address is valid"
            ]);
        }
    }

    public function reset_password(Request $request)
    {
        $email = $request->email;
        $token = $request->token;

        return view("home.reset-password", [
            "token" => $token,
            "email" => $email
        ]);
    }

    public function change_password(Request $request, $locale)
    {
        $validated = $request->validate([
            "new_password" => "bail|required|regex:/^[A-Z]{1}[a-zA-Z0-9]+[0-9]{1}[a-zA-Z0-9]+$/",
            "confirm_password" => "bail|required|same:new_password"
        ]);

        $email = $request->email;

        try {
            $user = User::where("email", $email)->first();

            if ($user === null) {
                return redirect()->back()->with([
                    "class" => "danger",
                    "message" => "User not recognized"
                ]);
            }

            $user->password = $validated["confirm_password"];
            $user->save();
            DB::table("password_reset_tokens")->where("email", $email)->delete();

            return redirect("/$locale/login")->with([
                "class" => "success",
                "message" => "Successful password reset"
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong, please try again or contact admin for assistance"
            ]);
        }
    }

    public function logout(Request $request, $locale)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect("/$locale/login")->with([
            'class' => 'success',
            'message' => 'You are logged out'
        ]);
    }

    public function user($id)
    {
        try {
            $existingUser = User::where("id", $id)->where("role", "DISTRIBUTOR")->firstOrFail();
            return response()->json([
                "data" => $existingUser
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Distributor doesn't exist"
            ], 400);
        }
    }

    public function image_change(Request $request)
    {
        $id = Auth::id();
        $currentUser = User::find($id);
        $request->validate([
            "image" => "bail|required|image"
        ]);

        try {
            if ($currentUser->image !== null) {
                Storage::delete($currentUser->image);
            }

            $path = $request->file("image")->store("public/user");
            $currentUser->image = $path;
            $currentUser->save();

            return response()->json([
                "code" => "success",
                "data" => $path
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "code" => "error"
            ], 500);
        }
    }

    public function personal_information(Request $request)
    {
        $id = Auth::id();
        $currentUser = User::find($id);
        $validated = $request->validate([
            "name" => "bail|required",
            "email" => "nullable|email"
        ]);

        try {
            $currentUser->name = $validated["name"];

            if ($currentUser->role === UserType::ADMIN->name || $currentUser->role === UserType::STOCKIST->name) {
                $currentUser->email = $validated["email"];
            }

            $currentUser->save();

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Updated personal information successfully"
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                //"message" => $e->getMessage()
                "message" => "Something went wrong, please contact developer for assistance"
            ]);
        }
    }

    public function password_change(Request $request)
    {
        $id = Auth::id();
        $currentUser = User::find($id);
        $validated = $request->validate([
            "current_password" => "bail|required",
            "new_password" => "bail|required|regex:/^[A-Z]{1}[a-zA-Z0-9]+[0-9]{1}[a-zA-Z0-9]+$/",
            "confirm_password" => "bail|required|same:new_password"
        ]);

        if (!Hash::check($validated["current_password"], $currentUser->password)) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Invalid current password"
            ]);
        }

        if (Hash::check($validated["new_password"], $currentUser->password)) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "New password must be different from current password"
            ]);
        }

        try {
            $currentUser->password = $validated["confirm_password"];
            $currentUser->save();

            return redirect()->back()->with([
                "class" => "success",
                "message" => "Password changed successfully"
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                "class" => "danger",
                "message" => "Something went wrong, please contact developer for assistance"
            ]);
        }
    }

    public function check($email)
    {
        try {
            $existingUser = User::where("email", $email)->first();

            if ($existingUser) {
                throw new \Exception("Email is already taken");
            }

            return response()->json([
                "code" => "success"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "code" => "error",
                "message" => $e->getMessage(),
            ], 500);
        }
    }
}
