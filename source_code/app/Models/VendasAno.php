<?php

namespace App\Models;

use App\Repositorio\VendaAno;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendasAno extends Model
{
    use HasFactory;

    public function enviarVendasAno($ano)
    {
        $vendaAno = new VendaAno();
        return  (array) $vendaAno->getVendaAno(strval($ano));
    }
}
