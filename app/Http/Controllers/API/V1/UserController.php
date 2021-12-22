<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Models\Chat;
use App\Models\Favorit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function contacts(Request $request)
    {
        $mobileNumbers = trim($request->mobile_numbers, '[]');
        $mobileNumbers = str_replace('+2','',$mobileNumbers);
        $mobileNumbers = str_replace('+966','',$mobileNumbers);
        $mobileNumbers = str_replace('+','',$mobileNumbers);
        $mobileNumbers = str_replace(' ', '', $mobileNumbers);
        $mobileNumbers = explode(',',$mobileNumbers);
        $contacts = User::whereIn('mobile_number',$mobileNumbers)->get();
        return response()->json(['contacts' => $contacts],200);
    }

    public function actives(Request $request)
    {

        $chats = Chat::where('user1', auth()->user()->id)
        ->orWhere('user2', auth()->user()->id)
        ->with('user1')
        ->with('user2')
        ->orderBy('updated_at', 'DESC')->get()->toArray();

    $user_chats = array_map(function ($chat) {

        $favorite_id = Favorit::where('favorite_person_id', ($chat['user1']['id'] == auth()->user()->id) ? $chat['user2'] : $chat['user1'])->where('user', auth()->user()->id)->first();
        $user = ($chat['user1']['id'] == auth()->user()->id) ? $chat['user2'] : $chat['user1'];
        if($user['is_active'] == true){
        return [
            'chat_id' => $chat['id'],
            'firebase_chat_id' => $chat['firebase_chat_id'],
            'last_message_received' => $chat['last_message_received'],
            'user2' =>$user,
            'favorite_id' => ($favorite_id) ? $favorite_id->id : null,
            'updated_at' => $chat['updated_at'],


        ];
    }
    }, $chats);



    return response()->json(['actives' => $user_chats], 200);
    }
    public function online(Request $request){
        $user = auth()->user();
        $user->is_active = true;
        $user->save();
        return response()->json(['user'=>$user],200);
    }
    public function offline(Request $request){
        $user = auth()->user();
        $user->is_active = false;
        $user->save();
        return response()->json(['user'=>$user],200);
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
