<?php

namespace App\Http\Controllers\API\V1;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChatRequest;
use App\Http\Requests\UpdateLastMessageRequest;
use App\Models\Chat;
use App\Models\Favorit;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $chats = Chat::where('user1',auth()->user()->id)
        ->orWhere('user2',auth()->user()->id)
        ->with('user1')
        ->with('user2')
        ->orderBy('updated_at', 'DESC')->get()->toArray();

        $user_chats = array_map(function($chat){

           $favorite_id = Favorit::where('favorite_person_id',($chat['user1']['id'] == auth()->user()->id) ? $chat['user2'] : $chat['user1'])->where('user',auth()->user()->id)->first();
            return [
                'chat_id' => $chat['id'],
                'firebase_chat_id' => $chat['firebase_chat_id'],
                'last_message_received' => $chat['last_message_received'],
                'user2' => ($chat['user1']['id'] == auth()->user()->id) ? $chat['user2'] : $chat['user1'],
                'favorite_id' => ($favorite_id) ? $favorite_id->id : null,
                'updated_at' => $chat['updated_at'],


            ];
        },$chats);



        return response()->json(['chats'=>$user_chats],200);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreChatRequest $request)
    {
        $chat = Chat::create([
            'user1' => $request->user1,
            'user2' => $request->user2,
            'firebase_chat_id' => $request->firebase_chat_id,
            'last_message_received' => $request->last_message_received,
        ]);

        return response()->json(['chat'=> $chat],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function show(Chat $chat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chat $chat)
    {
        $chat->update(['last_message_received'=>$request->last_message_received]);

        return response()->json(['chat'=> $chat],200);
    }
    public function updateLastMessage(UpdateLastMessageRequest $request){

        $chat = Chat::where('firebase_chat_id',$request->firebase_chat_id)->first();
        $chat->update(['last_message_received'=>$request->last_message_received]);
        return response()->json(['chat'=> $chat],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chat $chat)
    {
        //
    }
}
