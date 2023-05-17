<?php

namespace App\Repositorio;

use App\Util\HelperUtil;
use Illuminate\Support\Facades\DB;

class Venda
{
    public  function sqlVenda($ano = '')
    {
        $dataInit = strval($ano) . '-01-01 00:00:00';
        $dataFinal = strval($ano) . '-12-31 23:00:00';
        $sql = 'SELECT
        venda."CODIGO" as "codigo",venda."NUMERO" as "numero",venda."CFOP" as "cfop",venda."DATA" as "data", venda."CODCLIENTE" as "codcliente",
        venda."VALOR_PRODUTOS" as "valor_produtos", venda."TOTAL_NOTA" as "total_nota",venda."MODELO_NF" as "modelo_nf",venda."ESPECIE" as "especie",
        venda."CODCAIXA" as "codcaixa" ,venda."ITENS" as "itens",venda."DESCONTO" as "desconto", venda."CODFILIAL" as "codfilial",venda."DATA_SAIDA" as "data_saida",
        venda."CODVENDEDOR" as "codvendedor",venda."VALOR_RECEBIDO" as "valor_recebido", venda."TROCO" as "troco",venda."MEIO_CARTAODEB" as "meio_cartaodeb",
        venda."MEIO_CARTAOCRED" as "meio_cartaocred",venda."MEIO_DINHEIRO" as "meio_dinheiro",venda."MEIO_CHEQUEAP" as "meio_chequeap",
        venda."MEIO_CHEQUEAV" as "meio_chequeav",venda."MEIO_CREDIARIO" as "meio_crediario",venda."MEIO_OUTROS" as "meio_outros",
        venda."TROCO" as "troco",venda."VALOR_RECEBIDO" as "valor_recebido"
       FROM
       "C000061" as venda  where "DATA" between ' . " '$dataInit' " . ' and ' . " '$dataFinal' " .
            ' order by venda."CODIGO" desc; ';

        return $sql;
    }

    public function runSql($ano){
       return (array) DB::select($this->sqlVenda($ano));
    }

    public  function getVenda($ano)
    {
        $vendas = $this->runSql($ano);
        date_default_timezone_set('America/Sao_Paulo');
        $data =   date('Y-m-d H:i:s');
        $vendaMount = [];
        $select = DB::select('select * from lojas limit 1');
        foreach ($vendas as $venda) {
            $vendaMount[] = [
                'codigo' => HelperUtil::removerCaracteres($venda->codigo) ?? "",
                'numero'  => HelperUtil::removerCaracteres($venda->numero) ?? "",
                'cfop'  => HelperUtil::removerCaracteres($venda->cfop) ?? "",
                'data'  => HelperUtil::setData($venda->data) ?? date('Y-m-d H:i:s'),
                'codcliente' => HelperUtil::removerCaracteres($venda->codcliente) ?? "",
                'valor_produtos' => $venda->valor_produtos ?? 0,
                'total_nota'  => $venda->total_nota ?? 0,
                'modelo_nf'  => HelperUtil::removerCaracteres($venda->modelo_nf) ?? "",
                'especie'  => HelperUtil::removerCaracteres($venda->especie) ?? "",
                'codcaixa'  => HelperUtil::removerCaracteres($venda->codcaixa) ?? "",
                'itens'  => $venda->itens ?? 0,
                'desconto'  => $venda->desconto ?? 0,
                'codfilial'  => HelperUtil::removerCaracteres($venda->codfilial) ?? "",
                'data_saida'  => HelperUtil::setData($venda->data_saida) ?? $data,
                'codvendedor'  => HelperUtil::removerCaracteres($venda->codvendedor) ?? "",
                'valor_recebido'  => $venda->valor_recebido ?? 0,
                'troco'  => $venda->troco ?? 0,
                'meio_dinheiro'  => $venda->meio_dinheiro ?? 0,
                'meio_cartaodeb'  => $venda->meio_cartaodeb ?? 0,
                'meio_cartaocred'  => $venda->meio_cartaocred ?? 0,
                'meio_chequeap'  => $venda->meio_chequeap ?? 0,
                'meio_chequeav'  => $venda->meio_chequeav ?? 0,
                'meio_crediario'  => $venda->meio_crediario ?? 0,
                'meio_outros' => $venda->meio_outros ?? 0,
                'cnpj_cliente' => HelperUtil::removerMascara($select[0]->cnpj_cliente) ?? "",
                'cnpj_loja' => HelperUtil::removerMascara($select[0]->cnpj_loja) ?? "",
                'created_at' => $data,
                'updated_at' =>  $data
            ];
        }

        return $vendaMount;
    }

}
