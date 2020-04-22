<?php


namespace App;


use PDO;

class Connection
{
    /**
     * @return PDO
     */
    public static function getPDO(): PDO
    {
        return new PDO('mysql:dbname=blog_poo;host=127.0.0.1;port=8889', 'root', 'root',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ])
        ;
    }

}