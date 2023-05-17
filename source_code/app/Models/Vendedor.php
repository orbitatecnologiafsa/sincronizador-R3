<?php

namespace App\Models;

use App\Repositorio\Vendedor as RepositorioVendedor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    use HasFactory;

    public function enviarVendedores()
    {
        $vendedor = new  RepositorioVendedor();
        return  (array) $vendedor->getVendedor();
    }
}
