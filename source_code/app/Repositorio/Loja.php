<?php

namespace App\Repositorio;

use App\Models\Loja as ModelsLoja;
use App\Models\Usuario;
use App\Util\HelperUtil;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class Loja
{

    protected $certificado;
    protected $loja;
    protected $url;
    public function __construct()
    {
        $this->certificado = 'app/cacert.pem';
        $this->loja = new ModelsLoja();
        $this->url = "https://orbitadashboard.azurewebsites.net/api/";
        //$this->url = 'http://127.0.0.1:8000/api/';
    }

    public function getLoja($id)
    {
        return HelperUtil::getCredLoja($id);
    }

    public function cadastrarLoja($loja)
    {
        try {
            $autenticado = $this->autenticar();

            if (!empty($autenticado)) {
                $usuario = $this->getUsuario($autenticado, HelperUtil::removerMascara($loja['cnpj_cliente']));

                if (!isset($usuario['error'])) {
                    $loja = [
                        "cnpj_cliente" => HelperUtil::removerMascara($loja['cnpj_cliente']),
                        "nome_loja" => $loja['nome_loja'],
                        "cnpj_loja" => HelperUtil::removerMascara($loja['cnpj_loja'] ?? $this->gerarCnpjAleatorio()),
                        "id_cliente" => $usuario['id']
                    ];
                    return   $this->cadastroApi($autenticado, $loja);
                }

                return ["success" => false, "error" => $usuario['error']];
            }
        } catch (Exception $e) {
            //throw $th;
            return ["success" => false, "error" => $e->getMessage()];
        }
    }

    public function atualizarLoja($loja)
    {
        try {
            $autenticado = $this->autenticar();

            $select  = DB::select('select * from lojas limit 1');

            if (!is_null($autenticado)) {
                $usuario = $this->getUsuario($autenticado, HelperUtil::removerMascara($loja['cnpj_cliente']));

                if (!isset($usuario['error'])) {
                    $lojas = [
                        "cnpj_cliente" => HelperUtil::removerMascara($loja['cnpj_cliente']),
                        "nome_loja" => $loja['nome_loja'],
                        "cnpj_loja" => HelperUtil::removerMascara($loja['cnpj_loja'] ?? $this->gerarCnpjAleatorio()),
                        "id_cliente" => $usuario['id'],
                        "id" => $select[0]->id_loja_api,
                    ];

                    return  $this->atualizarApi($autenticado, $lojas, $select);
                }

                return ["success" => false, "error" => $usuario['error']];
            }
        } catch (Exception $e) {
            //throw $th;
            return ["success" => false, "error" => $e->getMessage()];
        }
    }
    public function gerarCnpjAleatorio()
    {
        $characters = 'ABCDEFGHIJKLMNOPQÇ*1F![]~;/z#@RSTUVWXYZ0123456789';
        $code = '';
        for ($i = 0; $i < 11; $i++) {
            $code .= md5($characters[rand(0, strlen($characters) - 1)]);
        }
        return md5($code);
    }

    public function cadastroApi($access_token, $loja)
    {

        try {

            $cliente = new Client([
                'verify' => storage_path($this->certificado), // Caminho completo para o arquivo cacert.pem
            ]);

            $response =  $cliente->post($this->url . 'auth/cadastro/loja', [
                "headers" => [
                    "Authorization" => "Bearer {$access_token}",
                    "Content-Type" => "application/json; charset=utf-8",
                ],
                "body" => json_encode($loja)
            ]);
            $responses = (json_decode($response->getBody()->getContents()));

            $loja['id_loja_api'] = $responses->id;
            DB::delete('delete from lojas');
            $this->loja->create($loja);
            return ["success" => true, "error" => "sa"];
        } catch (Exception $e) {

            return ["success" => false, "error" => $e->getMessage()];
        }
    }

    public function atualizarApi($access_token, $loja, $select)
    {

        try {

            $cliente = new Client([
                'verify' => storage_path($this->certificado), // Caminho completo para o arquivo cacert.pem
            ]);

            $response =  $cliente->post($this->url . 'auth/atualizar/loja', [
                "headers" => [
                    "Authorization" => "Bearer {$access_token}",
                    "Content-Type" => "application/json; charset=utf-8",
                ],
                "body" => json_encode($loja)
            ]);
            $responses = (json_decode($response->getBody()->getContents()));

            $loja['id_loja_api'] = $responses->valor_antigo->id;

            $this->loja->where('id', $select[0]->id)->update($loja);
            return ["success" => true, "error" => "sa"];
        } catch (Exception $e) {

            return ["success" => false, "error" => $e->getMessage()];
        }
    }

    public function autenticar()
    {
        try {
            $cliente  = new Client([
                'verify' => storage_path($this->certificado), // Caminho completo para o arquivo cacert.pem
            ]);
            $response = $cliente->post($this->url . 'auth/login', [
                "form_params" => [
                    "email" => env('SECRET_CONFIG_USERNAME'),
                    "password" => env('SECRET_CONFIG_PASS')
                ]
            ]);
            $responseData = json_decode($response->getBody(), true);
            $access_token = $responseData['access_token'];
            return $access_token;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getUsuario($access_token, $cnpj)
    {
        try {
            $usuario = new Usuario();

            $cliente = new Client([
                'verify' => storage_path($this->certificado), // Caminho completo para o arquivo cacert.pem
            ]);


            $getUsuario = $usuario->getCredencial($cnpj);


            $response =  $cliente->post($this->url . 'auth/user', [
                "headers" => [
                    "Authorization" => "Bearer {$access_token}",
                    "Content-Type" => "application/json; charset=utf-8",
                ],
                "body" => json_encode($getUsuario)
            ]);
            $responses = json_decode($response->getBody(), true);

            if (isset($responses['error'])) {
                return ['error' => $responses['error']];
            } else {
                return $responses;
            }
        } catch (Exception $e) {
            var_dump(['deu erro' => $e->getMessage()]);
            die();
        }
    }
}
