<?php

namespace App\Models;

use App\Repositorio\Usuario as RepositorioUsuario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $repositorio;



    public function getCredencial($cnpj ='')
    {   $usuario = new RepositorioUsuario();
        
        if(!empty($cnpj)){
            return $usuario->getUsuario($cnpj);
        }else{
            return $usuario->getUsuario();
        }

    }
}
