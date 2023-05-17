<?php

namespace App\Repositorio;

use App\Util\HelperUtil;
use Illuminate\Support\Facades\DB;

class Vendedor
{

    public static function sqlVendedor()
    {
        $sql = 'select
        "CODIGO" as "codigo", "NOME" as "nome"
        from
        "C000008" order by "C000008"."CODIGO" desc;
        ';
        return $sql;
    }

    public function runSql()
    {
        return (array) DB::select($this->sqlVendedor());
    }

    public  function getVendedor()
    {
        $vendedores = $this->runSql();
        date_default_timezone_set('America/Sao_Paulo');
        $data =   date('Y-m-d H:i:s');
        $vendedorMont = [];
        $select = DB::select('select * from lojas limit 1');
        foreach ($vendedores as  $vendedor) {
            $vendedorMont[] = [
                'nome_vendedor'=> $vendedor->nome,
                'codigo_vendedor' => HelperUtil::removerCaracteres($vendedor->codigo) ?? "s/n",
                'cnpj_cliente' => HelperUtil::removerMascara($select[0]->cnpj_cliente) ?? "",
                'cnpj_loja' => HelperUtil::removerMascara($select[0]->cnpj_loja) ?? "",
                'created_at' => $data,
                'updated_at' =>  $data
            ];
        }
        
        return $vendedorMont;
    }
}
