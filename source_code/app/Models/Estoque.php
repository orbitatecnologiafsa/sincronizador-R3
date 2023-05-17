<?php

namespace App\Models;

use App\Repositorio\Estoque as RepositorioEstoque;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    use HasFactory;

    public function enviarEstoque()
    {
        $estoque = new  RepositorioEstoque();
        return  (array) $estoque->getEstoque();
    }

}
