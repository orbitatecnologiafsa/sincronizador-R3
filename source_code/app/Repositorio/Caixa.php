<?php

namespace App\Repositorio;

use App\Util\HelperUtil;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class Caixa
{

    public  function sqlCaixa($ano = '')
    {
        $dataInit = strval($ano) . '-01-01 00:00:00';
        $dataFinal = strval($ano) . '-12-31 23:00:00';
        $sql = 'select "CODIGO" as "codigo"  , "CODCAIXA" as "codcaixa", "CODOPERADOR" as "codoperador", "DATA" as "data", "SAIDA" as "saida", "ENTRADA" as "entrada",
        "CODCONTA" as "codconta", "HISTORICO" as "historico", "MOVIMENTO" as "movimento", "VALOR" as "valor", "CODNFSAIDA" as "codnfsaida",
        "POSTO" as "posto", "CODIGO_VENDA" as "codigo_venda", "HORA" as "hora"
         FROM
        "C000044" where "DATA" between ' . " '$dataInit' " . ' and ' . " '$dataFinal' " .
            ' order by "C000044"."CODIGO" desc';
        return $sql;
    }

    public function runSql($ano)
    {
        return (array) DB::select($this->sqlCaixa($ano));
    }

    public  function getCaixa($ano)
    {
        $caixaMount = [];
        date_default_timezone_set('America/Sao_Paulo');
        $data =   date('Y-m-d H:i:s');
        $select = DB::select('select * from lojas limit 1');

        $caixas = $this->runSql($ano);

        foreach ($caixas as $caixa) {
            $caixaMount[] =  [
                'codigo' => HelperUtil::removerCaracteres($caixa->codigo) ?? 's/n',
                'codcaixa' => HelperUtil::removerCaracteres($caixa->codcaixa) ?? 's/n',
                'codoperador' => HelperUtil::removerCaracteres($caixa->codoperador) ?? 's/n',
                'data' => HelperUtil::setData($caixa->data) ?? $data,
                'saida' => $caixa->saida ?? 0,
                'entrada' => $caixa->entrada ?? 0,
                'codconta' => HelperUtil::removerCaracteres($caixa->codconta) ?? 's/n',
                'historico' => HelperUtil::removerCaracteres($caixa->historico) ?? 's/n',
                'movimento' => HelperUtil::removerCaracteres($caixa->movimento) ?? '',
                'valor' => $caixa->valor ?? 0,
                'codnfsaida' => HelperUtil::removerCaracteres($caixa->codnfsaida) ?? 's/n',
                'posto' => $caixa->posto ?? 0,
                'codigo_venda' => HelperUtil::removerCaracteres($caixa->codigo_venda) ?? 's/n',
                'hora' => HelperUtil::setData($caixa->data)  ?? date('Y-m-d H:i:s'),
                'cnpj_cliente' => HelperUtil::removerMascara($select[0]->cnpj_cliente) ?? "",
                'cnpj_loja' => HelperUtil::removerMascara($select[0]->cnpj_loja) ?? "",
                'created_at' => $data,
                'updated_at' =>  $data
            ];
        }


        return $caixaMount;
    }
}
