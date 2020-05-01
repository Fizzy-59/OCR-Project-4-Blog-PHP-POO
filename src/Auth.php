<?php


namespace App;


use App\Table\Exception\NotFoundException;

class Auth
{

    public static function check()
    {
        if(session_status() === PHP_SESSION_NONE)
        {
            session_start();
        }

        if(!isset($_SESSION['auth']))
        {
            throw new NotFoundException();
        }
    }

}