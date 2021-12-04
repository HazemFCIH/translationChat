<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function contacts(Request $request)
    {
        $mobileNumbers = trim($request->mobile_numbers, '[]');
        $mobileNumbers = str_replace('+','',$mobileNumbers);
        $mobileNumbers = str_replace(' ', '', $mobileNumbers);
        $mobileNumbers = explode(',',$mobileNumbers);
        $contacts = User::whereIn('mobile_number',$mobileNumbers)->get();
        return response()->json(['contacts' => $contacts],200);
    }
    public function checkUser(Request $request)
    {
        $user = User::where('mobile_number',$request->mobile_number)->first();
        return ($user) ? response()->json(['success'],200) :response()->json(['failed'],404);
    }
    public function userProfile(Request $request){

        return response()->json(['user' => auth()->user()],200);

    }
    public function allUser(Request $request){

        return response()->json(['users' => User::all()],200);

    }
    public function updateProfile(UpdateUserProfileRequest $request){

        $user = User::find(auth()->user()->id);

        if(isset($request->name) && !$request->hasFile('image_path')){
            $user->name = $request->name;
            $user->save();
            return $user->only(['id','name','email','image_url','updated_at']);
        }
        if(isset($request->name) && $request->hasFile('image_path')){
            $user->name = $request->name;
        }

        if(!$request->hasFile('image_path')) {
            return response()->json(['upload_file_not_found'], 400);
        }
        $file = $request->file('image_path');
        $path = $file->store('public/profile_images');
        $url = Storage::url($path);
        if(isset($user->image_path)){
        Storage::delete($user->image_path);

        }
        $user->image_path = $path;
        $user->image_url = asset($url);
        $user->save();
        return $user->only(['id','name','email','image_url','updated_at']);






    }

}
