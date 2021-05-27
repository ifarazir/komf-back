<?php

namespace App\Http\Controllers\User\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\Uploader\Uploader;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    private $uploader;

    public function __construct(Uploader $uploader)
    {
        $this->uploader = $uploader;
    }

    // User Register
    public function register(Request $request) {
        $validator  =   Validator::make($request->all(), [
            "fname"  =>  "required",
            "lname"  =>  "required",
            "email"  =>  "required|email",
            "phone"  =>  "required",
            "password"  =>  "required"
        ]);

        if($validator->fails()) {
            return response()->json(["status" => "failed", "validation_errors" => $validator->errors()]);
        }

        $inputs = $request->all();
        $inputs["password"] = Hash::make($request->password);

        $user   =   User::create($inputs);

        if(!is_null($user)) {
            return response()->json(["status" => "success", "message" => "Success! registration completed", "data" => $user]);
        }
        else {
            return response()->json(["status" => "failed", "message" => "Registration failed!"]);
        }       
    }

    // User login
    public function login(Request $request) {

        $validator = Validator::make($request->all(), [
            "email" =>  "required|email",
            "password" =>  "required",
        ]);

        if($validator->fails()) {
            return response()->json(["validation_errors" => $validator->errors()]);
        }

        $user = User::where("email", $request->email)->first();

        if(is_null($user)) {
            return response()->json(["status" => "failed", "message" => "Failed! email not found"]);
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;

            return response()->json(["status" => "success", "login" => true, "token" => $token, "data" => $user]);
        }
        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! invalid password"]);
        }
    }

    public function logout() {
        $user=Auth::user();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

        if(!is_null($user)) {
            return response()->json(["status" => "success", "message" => "Success! Logout completed"]);
        }
        else {
            return response()->json(["status" => "failed", "message" => "Whoops! no user found"]);
        }
    }

    // User Detail
    public function user() {
        $user=Auth::user();
        if(!is_null($user)) {
            if(isset($user->roles[0])){
                $user->role = $user->roles[0]->name;
            }
            else {
                $user->role = 'user';
            }
            if ($user->photo != null) {
                $user['photo_url'] = asset('storage/' . $user->photo->filePath());
            }
            return response()->json(["status" => "success", "data" => $user->makeHidden(['roles', 'photo', 'photo_id'])]);
        }

        else {
            return response()->json(["status" => "failed", "message" => "Whoops! no user found"]);
        }        
    }
    
    public function ChangePassword(Request $request) {
        $user=Auth::user();
        if ((Hash::check($request->old_password, $user->password)) == false) {
            return response()->json(["status" => "failed", "message" => "Old Password Incorrect!"]);
        }
        else {
            User::where('id', $user->id)->update(['password' => Hash::make($request->new_password)]);
            $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
            return response()->json(["status" => "success", "message" => "Password Changed and Logout Successfully!"]);
        }
    }
    
    public function ChangeProfile(Request $request) {
        if ($request->file('file') !== null) {
            $file = $this->uploader->upload();
            $user=Auth::user();
            $user->update(['photo_id' => $file->id]);
            return response()->json(["status" => "failed", "message" => "Profile Photo Updated Successfully!"]);
        }
        else {
            return response()->json(["status" => "failed", "message" => "Profile Photo Required!"]);
        }
    }
}