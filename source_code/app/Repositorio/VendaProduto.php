<?php

namespace App\Repositorio;

use App\Util\HelperUtil;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class VendaProduto
{

    public  function sqlVendaProduto($ano = '')
    {
        $dataInit = strval($ano) . '-01-01 00:00:00';
        $dataFinal = strval($ano) . '-12-31 23:00:00';
        $sql = 'select i."PRODUTO" as produto ,i."CODIGO" as cod_produto,i."PRECOVENDA" as preco_venda, i."PRECOCUSTO"
        as preco_custo,v."CODNOTA" as cod_nota ,v."QTDE" as qtde ,v."TOTAL" as total,v."CODPRODUTO" as cod_produto,
        v."UNITARIO" as unitario, r."DATA" as data
        from "C000062" as v
        join "C000025" as i on i."CODIGO" = v."CODPRODUTO"
        join "C000061" as r on v."CODNOTA" = r."CODIGO"
        where  r."DATA" between  ' . " '$dataInit' " . ' and ' . " '$dataFinal' " .
            'group by i."PRODUTO",i."CODIGO",i."PRECOVENDA",
        v."CODNOTA",v."QTDE",v."TOTAL",v."CODPRODUTO",i."PRECOCUSTO",v."UNITARIO",r."CODIGO",r."DATA"';
        return $sql;
    }

    public function runSql($ano)
    {
        return (array) DB::select($this->sqlVendaProduto($ano));
    }

    public  function getVendaProduto($ano)
    {
        $vendaProdutoMount = [];
        date_default_timezone_set('America/Sao_Paulo');
        $data =   date('Y-m-d H:i:s');
        $select = DB::select('select * from lojas limit 1');
        $vendasProduto = $this->runSql($ano);
        foreach ($vendasProduto as $vp) {
            $vendaProdutoMount[] =  [
                'produto' => HelperUtil::removerCaracteres($vp->produto) ?? 's/n',
                'cod_produto' => HelperUtil::removerCaracteres($vp->cod_produto) ?? 's/n',
                'preco_venda' => $vp->preco_venda ?? 0,
                'preco_custo' => $vp->preco_custo ?? 0,
                'cod_nota' => HelperUtil::removerCaracteres($vp->cod_nota) ?? 's/n',
                'qtde' => $vp->qtde ?? 0,
                'total' => $vp->total ?? 0,
                'unitario' => $vp->unitario ?? 0,
                'data' => $vp->data ?? $data,
                'cnpj_cliente' => HelperUtil::removerMascara($select[0]->cnpj_cliente) ?? "",
                'cnpj_loja' => HelperUtil::removerMascara($select[0]->cnpj_loja) ?? "",
                'created_at' => $data,
                'updated_at' =>  $data
            ];
        }

        return $vendaProdutoMount;
    }
}
