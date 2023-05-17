<?php

namespace App\Models;

use App\Repositorio\Venda as RepositorioVenda;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Venda extends Model
{
    use HasFactory;

    protected $repositorio;

    public function enviarVendas($ano)
    {
        $venda = new RepositorioVenda();
        return  (array) $venda->getVenda(strval($ano));
    }
}
