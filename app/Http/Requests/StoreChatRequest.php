<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
                'user1' => 'required|exists:users,id',
                'user2' => 'required|exists:users,id',
                'firebase_chat_id' => 'required|unique:chats,firebase_chat_id',
                'last_message_received' => 'sometimes|required',
        ];
    }
}
