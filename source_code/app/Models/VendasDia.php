<?php

namespace App\Models;

use App\Repositorio\VendaDia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendasDia extends Model
{
    use HasFactory;
    public function enviarVendasDia($ano)
    {
        $vendaDia = new VendaDia();
        return  (array) $vendaDia->getVendaDia(strval($ano));
    }
}
