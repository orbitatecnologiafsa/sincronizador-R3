<?php

namespace App\Models;

use App\Repositorio\VendaProduto as RepositorioVendaProduto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendaProduto extends Model
{
    use HasFactory;

    protected $repositorio;

    public function enviarVendasProduto($ano)
    {
        $venda = new RepositorioVendaProduto();
        return  (array) $venda->getVendaProduto(strval($ano));
    }
}
