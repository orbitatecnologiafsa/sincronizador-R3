<?php

namespace App\Models;

use App\Repositorio\Caixa as RepositorioCaixa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caixa extends Model
{
    use HasFactory;


    protected $repositorio;

  
    public function enviarCaixas($ano)
    {
        $caixa = new RepositorioCaixa ();
        return  (array) $caixa->getCaixa(strval($ano));
    }


}
