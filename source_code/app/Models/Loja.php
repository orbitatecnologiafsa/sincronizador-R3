<?php

namespace App\Models;

use App\Repositorio\Loja as RepositorioLoja;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loja extends Model
{
    use HasFactory;



    protected $fillable = [
        'nome_loja',
        'cnpj_cliente',
        'cnpj_loja',
        'id_cliente',
        'id_loja_api'
    ];



    public function getLoja($id)
    {
        $repo = new RepositorioLoja();
        return $repo->getLoja($id);
    }
}
