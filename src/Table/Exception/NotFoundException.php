<?php


namespace App\Table\Exception;


use Exception;

class NotFoundException extends Exception
{
    /**
     * NotFoundException constructor.
     *
     * @param string $table
     * @param $id
     */
    public function __construct(string $table, $id)
    {
        $this->message = "Aucun enregistrement ne correspond à l'id #$id dans la table '$table'";
    }
}