<?php

namespace App\Console\Commands;

use App\Models\Caixa;
use App\Models\Estoque;
use App\Models\Loja;
use App\Models\Usuario;
use App\Models\Venda;
use App\Models\VendaProduto;
use App\Models\VendasAno;
use App\Models\VendasDia;
use App\Models\Vendedor;
use App\Util\HelperUtil;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class MainSinc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inicio do serviço';

    // protected $url = "https://orbitadashboard.azurewebsites.net/api/";

    protected $url = "http://127.0.0.1:8000/api/";
    protected $certificado = "app/cacert.pem";


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        try {
            date_default_timezone_set('America/Sao_Paulo');
            $hora_init = date('H:i:s');
            if ($this->statusServidor()) {
                //autenticador da api
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
                //busca o usuario master
                $user = $this->getUsuario($access_token);

                //busca ou cadastra a loja
                echo "Serviço cadastrar loja! \n";
                $this->getLoja($access_token, $user['id']);
                echo "Serviço cadastar vendedor! \n";
                $this->cadastrarVendedores($access_token);
                //deleta a venda
                //   echo "Serviço deletar  venda! \n";
                //   $this->deleteBigData($access_token, 'venda');
                //cadastra venda
                echo "Cadastro venda diaria \n";
                $this->cadastrarVendasDia($access_token);

                echo "Cadastro venda ano \n";
                $this->cadastrarVendasAno($access_token);
                echo "Serviço cadastrar venda! \n";
                $this->cadastrarVendas($access_token);
                //deleta o caixa
                //    echo "Serviço deletar caixa! \n";
                // $this->deleteBigData($access_token, 'caixa');
                //cadastar caixa
                echo "Serviço cadastrar caixa! \n";
                $this->cadastrarCaixas($access_token);
                //deletar estoque
                //   echo "Serviço deletar estoque! \n";
                // $this->deleteBigData($access_token, 'estoque');
                //cadastrar estoque
                echo "Serviço cadastrar estoque! \n";
                $this->cadastrarEstoque($access_token);

                $hora_final =  date('H:i:s');
                echo "Serviço finalizado! \n time init {$hora_init}  time final {$hora_final} \n";
            }
        } catch (Exception $e) {
            var_dump(['deu erro' => $e->getMessage()]);
            die();
        }
    }

    public function getLoja($access_token, $id)
    {
        try {
            $loja = new Loja();

            $cliente = new Client([
                'verify' => storage_path($this->certificado), // Caminho completo para o arquivo cacert.pem
            ]);

            $getLoja = $loja->getLoja($id);

            $response =  $cliente->post($this->url . 'auth/cadastro/loja', [
                "headers" => [
                    "Authorization" => "Bearer {$access_token}",
                    "Content-Type" => "application/json; charset=utf-8",
                ],
                "body" => json_encode($getLoja)
            ]);
            var_dump(json_decode($response->getBody()->getContents()));
            return true;
        } catch (Exception $e) {
            var_dump(['deu erro' => $e->getMessage()]);
            die();
        }
    }
    public function getUsuario($access_token)
    {

        try {
            $usuario = new Usuario();

            $cliente = new Client([
                'verify' => storage_path($this->certificado), // Caminho completo para o arquivo cacert.pem
            ]);

            $getUsuario = $usuario->getCredencial();

            $response =  $cliente->post($this->url . 'auth/user', [
                "headers" => [
                    "Authorization" => "Bearer {$access_token}",
                    "Content-Type" => "application/json; charset=utf-8",
                ],
                "body" => json_encode($getUsuario)
            ]);
            return (json_decode($response->getBody(), true));
        } catch (Exception $e) {
            var_dump(['deu erro' => $e->getMessage()]);
            die();
        }
    }

    public function cadastrarVendas($access_token = "")
    {
       // venda-produto
        try {
            $venda = new Venda();
            $vendasProduto = new VendaProduto();
            $qtdAtualP = 0;
            $responses = [];
            $qtdAtual = 0;
            foreach (HelperUtil::dataArry() as $ano) {
                echo "Cadastro Venda ano $ano \n";
                $vendas = $venda->enviarVendas($ano);
                $produtos = $vendasProduto->enviarVendasProduto($ano);
                $chuncks = array_chunk($vendas, 1000);
                $chuncksProd = array_chunk($produtos,1000);
                $cliente  = new Client([
                    'verify' => storage_path($this->certificado), // Caminho completo para o arquivo cacert.pem
                ]);
                foreach ($chuncks as $chunk) {
                    $response =  $cliente->post($this->url . 'auth/cadastro/venda', [
                        "headers" => [
                            "Authorization" => "Bearer {$access_token}",
                            "Content-Type" => "application/json; charset=utf-8",
                        ],
                        "body" => json_encode($chunk)
                    ]);
                    $responses[] = json_decode($response->getBody()->getContents());
                    $qtdAtual += count($chunk);
                    var_dump($responses);
                    echo "Qtd Venda ano $ano  $qtdAtual \n";
                    sleep(3);
                }
                echo "Cadastro finalizado Venda ano $ano \n";
                foreach ($chuncksProd as $chunkP) {
                    $response =  $cliente->post($this->url . 'auth/cadastro/venda-produto', [
                        "headers" => [
                            "Authorization" => "Bearer {$access_token}",
                            "Content-Type" => "application/json; charset=utf-8",
                        ],
                        "body" => json_encode($chunkP)
                    ]);
                    $responses[] = json_decode($response->getBody()->getContents());
                    $qtdAtualP += count($chunkP);
                    var_dump($responses);
                    echo "Qtd vendas produtos por ano  ->  $ano  $qtdAtualP \n";
                    echo "Cadastro finalizado vendas produto por  ano ->  $ano \n";
                    sleep(3);
                }
            }
        } catch (Exception $e) {
            var_dump(['deu erro' => $e->getMessage()]);
            die();
        }
    }

    public function cadastrarCaixas($access_token = "")
    {
        try {
            $caixa = new Caixa();
            $responses = [];
            $qtdAtual = 0;
            foreach (HelperUtil::dataArry() as $ano) {
                echo "Cadastro caixa ano $ano \n";
                $caixas = $caixa->enviarCaixas($ano);
                $chuncks = array_chunk($caixas, 1000);
                $cliente  = new Client([
                    'verify' => storage_path($this->certificado), // Caminho completo para o arquivo cacert.pem
                ]);
                foreach ($chuncks as $chunk) {
                    $response =  $cliente->post($this->url . 'auth/cadastro/caixa', [
                        "headers" => [
                            "Authorization" => "Bearer {$access_token}",
                            "Content-Type" => "application/json; charset=utf-8",
                        ],
                        "body" => json_encode($chunk)
                    ]);
                    $responses[] = json_decode($response->getBody()->getContents());
                    $qtdAtual += count($chunk);
                    var_dump($responses);
                    echo "Qtd Caixa ano $ano  $qtdAtual \n";
                    echo "Cadastro finalizado Caixa ano $ano \n";
                    sleep(3);
                }
            }
        } catch (Exception $e) {
            var_dump(['deu erro' => $e->getMessage()]);
            die();
        }
    }

    public function cadastrarEstoque($access_token = "")
    {
        try {
            $estoque = new Estoque();
            $responses = [];
            $qtdAtual = 0;

            echo "Cadastro Estoque \n";
            $estoques = $estoque->enviarEstoque();
            $chuncks = array_chunk($estoques, 1000);
            $cliente  = new Client([
                'verify' => storage_path($this->certificado), // Caminho completo para o arquivo cacert.pem
            ]);
            foreach ($chuncks as $chunk) {
                $response =  $cliente->post($this->url . 'auth/cadastro/estoque', [
                    "headers" => [
                        "Authorization" => "Bearer {$access_token}",
                        "Content-Type" => "application/json; charset=utf-8",
                    ],
                    "body" => json_encode($chunk)
                ]);
                $responses[] = json_decode($response->getBody()->getContents());
                $qtdAtual += count($chunk);
                var_dump($responses);
                echo "Qtd Estoque  $qtdAtual \n";
                echo "Cadastro finalizado estoque \n";
                sleep(3);
            }
        } catch (Exception $e) {
            var_dump(['deu erro' => $e->getMessage()]);
            die();
        }
    }

    public function statusServidor()
    {
        try {
            echo "verificando conexão internet \n";
            if (HelperUtil::verificarInternet()) {
                $cliente = new Client([
                    'verify' => storage_path($this->certificado), // Caminho completo para o arquivo cacert.pem
                ]);
                $response =     $cliente->get($this->url . 'servidor/status', [
                    "headers" => [
                        "Content-Type" => "application/json; charset=utf-8"
                    ]
                ]);
                $responseData = json_decode($response->getBody()->getContents(), true);

                if (isset($responseData['service']) && $responseData['service']) {
                    echo "Servidor ok!  \n";
                    return true;
                } else {
                    echo "Servidor fora do ar!  \n";
                    return false;
                }
            } else {
                echo "Sem conexão com a internet, verifique sua conexão \n";
                return false;
            }
        } catch (Exception $e) {
            var_dump(['deu erro' => $e->getMessage()]);
            die();
        }
    }

    public function deleteBigData($access_token, $prefix)
    {
        try {
            $cliente = new Client([
                'verify' => storage_path($this->certificado), // Caminho completo para o arquivo cacert.pem
            ]);
            $delete = HelperUtil::configDelet();
            $response = $cliente->post($this->url . "auth/cadastro/{$prefix}/delete/bigdata", [
                "headers" => [
                    "Authorization" => "Bearer {$access_token}",
                    "Content-Type" => "application/json; charset=utf-8",
                ],
                "body" => json_encode($delete)
            ]);
            var_dump(json_decode($response->getBody()->getContents()));
            return true;
        } catch (Exception $e) {
            var_dump(['deu erro' => $e->getMessage()]);
            die();
        }
    }


    public function cadastrarVendedores($access_token = "")
    {
        try {
            $vendedor = new Vendedor();
            $responses = [];
            $qtdAtual = 0;

            echo "Cadastro Vendedor \n";
            $vendedores = $vendedor->enviarVendedores();
            $chuncks = array_chunk($vendedores, 1000);
            $cliente  = new Client([
                'verify' => storage_path($this->certificado), // Caminho completo para o arquivo cacert.pem
            ]);
            foreach ($chuncks as $chunk) {
                $response =  $cliente->post($this->url . 'auth/cadastro/vendedor', [
                    "headers" => [
                        "Authorization" => "Bearer {$access_token}",
                        "Content-Type" => "application/json; charset=utf-8",
                    ],
                    "body" => json_encode($chunk)
                ]);
                $responses[] = json_decode($response->getBody()->getContents());
                $qtdAtual += count($chunk);
                var_dump($responses);
                echo "Qtd vendedor  $qtdAtual \n";
                echo "Cadastro finalizado vendedor \n";
                sleep(3);
            }
        } catch (Exception $e) {
            var_dump(['deu erro' => $e->getMessage()]);
            die();
        }
    }

    public function cadastrarVendasAno($access_token = "")
    {
        try {
            $vendasAno = new VendasAno();

            $responses = [];
            $qtdAtual = 0;


            foreach (HelperUtil::dataArry() as $ano) {
                echo "Cadastro vendas por ano ->  ano $ano \n";
                $venda = $vendasAno->enviarVendasAno($ano);

                $chuncks = array_chunk($venda, 1000);

                $cliente  = new Client([
                    'verify' => storage_path($this->certificado), // Caminho completo para o arquivo cacert.pem
                ]);
                foreach ($chuncks as $chunk) {
                    $response =  $cliente->post($this->url . 'auth/cadastro/venda-ano', [
                        "headers" => [
                            "Authorization" => "Bearer {$access_token}",
                            "Content-Type" => "application/json; charset=utf-8",
                        ],
                        "body" => json_encode($chunk)
                    ]);
                    $responses[] = json_decode($response->getBody()->getContents());
                    $qtdAtual += count($chunk);
                    var_dump($responses);
                    echo "Qtd vendas por ano  ->  $ano  $qtdAtual \n";
                    echo "Cadastro finalizado vendas por  ano ->  $ano \n";
                    sleep(3);
                }

            }
        } catch (Exception $e) {
            var_dump(['deu erro' => $e->getMessage()]);
            die();
        }
    }

    public function cadastrarVendasDia($access_token = "")
    {
        try {
            $vendaDia = new VendasDia();
            $responses = [];
            $qtdAtual = 0;
            foreach (HelperUtil::dataArry() as $ano) {
                echo "Cadastro vendas por dia    ano $ano \n";
                $venda = $vendaDia->enviarVendasDia($ano);
                $chuncks = array_chunk($venda, 1000);
                $cliente  = new Client([
                    'verify' => storage_path($this->certificado), // Caminho completo para o arquivo cacert.pem
                ]);
                foreach ($chuncks as $chunk) {
                    $response =  $cliente->post($this->url . 'auth/cadastro/venda-dia', [
                        "headers" => [
                            "Authorization" => "Bearer {$access_token}",
                            "Content-Type" => "application/json; charset=utf-8",
                        ],
                        "body" => json_encode($chunk)
                    ]);
                    $responses[] = json_decode($response->getBody()->getContents());
                    $qtdAtual += count($chunk);
                    var_dump($responses);

                    echo "Qtd vendas por dia  ano -> ano $ano  $qtdAtual \n";
                    echo "Cadastro finalizado vendas por dia ano ->  $ano \n";
                    sleep(3);
                }
            }
        } catch (Exception $e) {
            var_dump(['deu erro' => $e->getMessage()]);
            die();
        }
    }

}
