<?php


namespace App;


class Validator extends \Valitron\Validator
{
    /**
     * @param  string $field
     * @param  string $message
     * @param  array  $params
     * @return array
     */
    protected function checkAndSetLabel($field, $message, $params)
    {
        return str_replace('{field}', '', $message);
    }
}