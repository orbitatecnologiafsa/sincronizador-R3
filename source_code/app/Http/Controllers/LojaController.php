<?php

namespace App\Http\Controllers;

use App\Http\Requests\LojaRequest;
use App\Models\Loja as ModelsLoja;
use App\Repositorio\Loja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LojaController extends Controller
{

    protected $repositorio;
    protected $path;
    public function __construct()
    {
        $this->repositorio = new Loja();
        $this->path = 'loja/';
    }

    public function cadastro()
    {
        return view($this->path . 'cadastro');
    }
    public function minhaLoja()
    {
        $loja = DB::select('select * from lojas limit 1');

        if(count($loja) > 0){
            return view($this->path . 'update',['loja' => $loja[0]]);
        }
        return redirect()->to('/')->with('msg-error-cadastro', 'Cadastre uma loja!');
    }

    public function cadastrarLoja(LojaRequest $req)
    {
        $cadastro = $this->repositorio->cadastrarLoja($req->all());

        if ($cadastro['success'] == true) {
            return redirect()->to('/')->with('msg-success-cadastro', 'Loja cadastrada com sucesso!')->withInput();
        }

        return redirect()->to('/')->with('msg-error-cadastro', $cadastro['error']);
    }

    public function atualizar(LojaRequest $req)
    {
        $atualizar = $this->repositorio->atualizarLoja($req->all());

        if ($atualizar['success'] == true) {
            return redirect()->to('/atualizar')->with('msg-success-atualizar', 'Loja atualizada com sucesso!')->withInput();
        }

        return redirect()->to('/atualizar')->with('msg-error-atualizar', $atualizar['error']);
    }
}
