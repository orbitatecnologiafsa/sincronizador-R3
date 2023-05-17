<?php

namespace App\Repositorio;

use App\Util\HelperUtil;
use Illuminate\Support\Facades\DB;

class Estoque
{
    public static function sqlEstoque()
    {
        $sql = 'select
        "CODIGO" as "codigo", "CODBARRA" as "codigobarra","PRODUTO" as "produto","UNIDADE" as "unidade",
        "CODGRUPO" as "codgrupo","DATA_ULTIMACOMPRA" as "data_ultimacompra","NOTAFISCAL" as "notafiscal",
        "PRECOCUSTO" as "precocusto","PRECOVENDA" as "precovenda","ESTOQUE" as "estoque","UNIDADE_ATACADO" as "unidade_atacado",
        "QTDE_EMBALAGEM" as "qtde_embalagem","TIPO" as "tipo","CODFORNECEDOR" as "codfornecedor"
        from
        "C000025" order by "C000025"."CODIGO" desc;
        ';
        return $sql;
    }

    public function runSql()
    {
        return (array) DB::select($this->sqlEstoque());
    }

    public  function getEstoque()
    {

        $estoque = $this->runSql();
        date_default_timezone_set('America/Sao_Paulo');
        $data =   date('Y-m-d H:i:s');
        $estoqueMont = [];
        $select = DB::select('select * from lojas limit 1');
        foreach ($estoque as  $produto) {
            $estoqueMont[] = [
                'codigo' => HelperUtil::removerCaracteres($produto->codigo) ?? "s/n",
                'codigobarra' => HelperUtil::removerCaracteres($produto->codigobarra) ?? "s/n",
                'produto' => HelperUtil::removerCaracteres($produto->produto) ?? "s/n",
                'unidade' => HelperUtil::removerCaracteres($produto->unidade) ?? "s/n",
                'codgrupo' => HelperUtil::removerCaracteres($produto->codgrupo) ?? "s/n",
                'data_ultimacompra' => HelperUtil::setData($produto->data_ultimacompra) ?? date('Y-m-d H:i:s'),
                'notafiscal' => HelperUtil::removerCaracteres($produto->notafiscal) ?? "s/n",
                'precocusto' => $produto->precocusto ?? 0.0,
                'precovenda' => $produto->precovenda ?? 0.0,
                'estoque' => $produto->estoque ?? 0,
                'unidade_atacado' => HelperUtil::removerCaracteres($produto->unidade_atacado) ?? "s/n",
                'qtde_embalagem' => HelperUtil::removerCaracteres($produto->qtde_embalagem) ?? "s/n",
                'tipo' => HelperUtil::removerCaracteres($produto->tipo) ?? "s/n",
                'codfornecedor' => HelperUtil::removerCaracteres($produto->codfornecedor) ?? "s/n",
                'cnpj_cliente' => HelperUtil::removerMascara($select[0]->cnpj_cliente) ?? "",
                'cnpj_loja' => HelperUtil::removerMascara($select[0]->cnpj_loja) ?? "",
                'created_at' => $data,
                'updated_at' =>  $data
            ];
        }
        return $estoqueMont;
    }
}
