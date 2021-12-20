<?php

namespace Services\FcmService;

class FcmService
{

    private $user;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    public function sendNotification($payload){


    }


}
