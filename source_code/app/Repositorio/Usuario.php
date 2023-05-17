<?php

namespace App\Repositorio;

use App\Util\HelperUtil;

class Usuario
{


    public function getUsuario($cnpj ='')
    {

        $usuario = HelperUtil::getCred($cnpj);

        $errors = [];
        foreach ($usuario as $key => $value) {
            if (empty($usuario[$key])) {
                $errors[$key] = "o campo {$key} precisa de um valor!";
            }
        }
        if (count($errors) == 0) {
            return $usuario;
        } else {
            var_dump($errors);
            die();
        }
    }
}
