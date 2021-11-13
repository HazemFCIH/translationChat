<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TranslateRequest;
use JoggApp\GoogleTranslate\GoogleTranslateFacade;

class TranslateController extends Controller
{
    protected function translateMessage(TranslateRequest $request)
    {

        $message = $request->message;
        $target  = $request->target;
        $translatedText = GoogleTranslateFacade::translate($message, $target);
        return response(['translated_message' => $translatedText['translated_text']],200) ;
    }
}
