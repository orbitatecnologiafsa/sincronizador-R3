<?php

namespace App\Repositorio;

use App\Util\HelperUtil;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class VendaDia
{

    public  function sqlVendaDia($ano = '')
    {
        $dia = $ano.'-'.date('m-d');
        //$dia = '2023-05-16';
       // var_dump($dia);die();
        $sql = 'SELECT i."PRODUTO" as produto, i."CODIGO" as codigo,i."PRECOVENDA" as preco_venda ,i."ESTOQUE" as estoque,i."PRECOCUSTO" as preco_custo,
        r."NUMERO" as numero, v."CODNOTA" as codnota, EXTRACT(YEAR FROM r."DATA") as ano , r."DATA"as data,
        SUM(i."PRECOVENDA"*v."QTDE") as valor_total, SUM(v."QTDE") AS total_vendido
        FROM "C000062" as v
        JOIN "C000025" as i ON v."CODPRODUTO" = i."CODIGO"
        JOIN "C000061" as r ON r."NUMERO" = v."CODNOTA"
        WHERE (v."QTDE") > 1 AND DATE(r."DATA") = '."'" .$dia."'".'
        GROUP BY i."PRODUTO",r."DATA",r."TOTAL_NOTA",r."NUMERO",v."CODNOTA",i."CODIGO",i."PRECOVENDA",i."ESTOQUE",i."PRECOCUSTO"
        ORDER BY total_vendido  DESC limit 10;';
        return $sql;
    }

    public function runSql($ano)
    {
        return (array) DB::select($this->sqlVendaDia($ano));
    }

    public  function getVendaDia($ano)
    {
        $vendaDiaMount = [];
        date_default_timezone_set('America/Sao_Paulo');
        $data =   date('Y-m-d H:i:s');
        $select = DB::select('select * from lojas limit 1');

        $vendasDia = $this->runSql($ano);

        foreach ($vendasDia as $vendaDia) {
            $vendaDiaMount[] =  [
                'codigo' => HelperUtil::removerCaracteres($vendaDia->codigo) ?? 's/n',
                'produto' => HelperUtil::removerCaracteres($vendaDia->produto) ?? 's/n',
                'preco_venda' => $vendaDia->preco_venda ?? 0,
                'preco_custo' => $vendaDia->preco_custo ?? 0,
                'numero' => HelperUtil::removerCaracteres($vendaDia->numero) ?? 's/n',
                'codnota' => HelperUtil::removerCaracteres($vendaDia->codnota) ?? 's/n',
                'ano' => HelperUtil::removerCaracteres($vendaDia->ano) ?? 's/n',
                'valor_total' => $vendaDia->valor_total ?? 0,
                'total_vendido' => $vendaDia->total_vendido ?? 0,
                'data' => $vendaDia->data ?? $data,
                'total_vendido' => $vendaDia->total_vendido ?? 0,
                'estoque' => $vendaDia->estoque ?? 0,
                'cnpj_cliente' => HelperUtil::removerMascara($select[0]->cnpj_cliente) ?? "",
                'cnpj_loja' => HelperUtil::removerMascara($select[0]->cnpj_loja) ?? "",
                'created_at' => $data,
                'updated_at' =>  $data
            ];
        }



        return $vendaDiaMount;
    }
}
