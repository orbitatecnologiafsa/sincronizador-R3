<?php

namespace App\Repositorio;

use App\Util\HelperUtil;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class VendaAno
{

    public  function sqlVendaAno($ano = '')
    {
        $sql = 'SELECT i."PRODUTO" as produto, i."CODIGO" as codigo,i."PRECOVENDA" as preco_venda ,i."PRECOCUSTO" as preco_custo,
        r."NUMERO" as numero, v."CODNOTA" as codnota, EXTRACT(YEAR FROM r."DATA") as ano ,
        SUM(i."PRECOVENDA"*v."QTDE") as valor_total, SUM(v."QTDE") AS total_vendido
        FROM "C000062" as v
        JOIN "C000025" as i ON v."CODPRODUTO" = i."CODIGO"
        JOIN "C000061" as r ON r."NUMERO" = v."CODNOTA"
        where (v."QTDE") > 1 and EXTRACT(YEAR FROM r."DATA") = '.$ano.'
        GROUP BY i."PRODUTO",r."DATA",r."TOTAL_NOTA",r."NUMERO",v."CODNOTA",i."PRECOVENDA",i."PRECOCUSTO",i."CODIGO"
        ORDER BY ano,total_vendido  DESC limit 10;';
        return $sql;
    }

    public function runSql($ano)
    {
        return (array) DB::select($this->sqlVendaAno($ano));
    }

    public  function getVendaAno($ano)
    {
        $vendaAnoMount = [];
        date_default_timezone_set('America/Sao_Paulo');
        $data =   date('Y-m-d H:i:s');
        $select = DB::select('select * from lojas limit 1');

        $vendasAno = $this->runSql($ano);

        foreach ($vendasAno as $vendaAno) {
            $vendaAnoMount[] =  [
                'codigo' => HelperUtil::removerCaracteres($vendaAno->codigo) ?? 's/n',
                'produto' => HelperUtil::removerCaracteres($vendaAno->produto) ?? 's/n',
                'preco_venda' => $vendaAno->preco_venda ?? 0,
                'preco_custo' => $vendaAno->preco_custo ?? 0,
                'numero' => HelperUtil::removerCaracteres($vendaAno->numero) ?? 's/n',
                'codnota' => HelperUtil::removerCaracteres($vendaAno->codnota) ?? 's/n',
                'ano' => HelperUtil::removerCaracteres($vendaAno->ano) ?? 's/n',
                'valor_total' => $vendaAno->valor_total ?? 0,
                'total_vendido' => $vendaAno->total_vendido ?? 0,
                'cnpj_cliente' => HelperUtil::removerMascara($select[0]->cnpj_cliente) ?? "",
                'cnpj_loja' => HelperUtil::removerMascara($select[0]->cnpj_loja) ?? "",
                'created_at' => $data,
                'updated_at' =>  $data
            ];
        }



        return $vendaAnoMount;
    }
}
