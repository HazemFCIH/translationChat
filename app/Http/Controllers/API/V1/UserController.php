<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function contacts(Request $request)
    {
        $contacts = User::whereIn('mobile_number',$request->mobile_numbers)->get();
        return response()->json(['contacts' => $contacts],200);
    }
    public function checkUser(Request $request)
    {
        $user = User::where('mobile_number',$request->mobile_number)->first();
        return ($user) ? response()->json(['success'],200) :response()->json(['failed'],404);
    }

}
