<?php


namespace App\Table\Exception;


use Exception;
use Throwable;

class NotFoundException extends Exception
{
    public function __construct(string $table, int $id)
    {
        $this->message = "Aucun enrengistrement ne correspond à l'id #$id dans la table '$table'";
    }

}