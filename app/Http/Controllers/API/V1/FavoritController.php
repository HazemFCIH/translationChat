<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFavoritePerson;
use App\Models\Favorit;
use App\Models\User;
use Illuminate\Http\Request;

class FavoritController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $favorites = Favorit::where('user',auth()->user()->id)->with('favorite')->get(['id','favorite_person_id']);



        return response(['favorites'=>$favorites],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFavoritePerson $request)
    {
        $favorite = Favorit::create([
            'user' => auth()->user()->id,
            'favorite_person_id' => $request->favorite_person_id,
        ]);

        return response()->json(['favorite'=> $favorite],201);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Favorit  $favorit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Favorit $favorite)
    {
        $favorite->delete();
        return response()->json(['deleted'],200);
    }
}
