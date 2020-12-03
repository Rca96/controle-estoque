<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Api extends REST_Controller {
    
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('model_estoque');
        $this->load->model('model_produto');
        $this->load->model('model_login');
        $this->load->model('model_movimento');
      
     

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['user_get']['limit'] = 5000; // 500 requests per hour per user/key
        $this->methods['user_post']['limit'] = 1000; // 100 requests per hour per user/key
        $this->methods['user_put']['limit'] = 1000; // 100 requests per hour per user/key
        $this->methods['ocorrencia_post']['limit'] = 5000; // 500 requests per hour per ocorrencia/key
        //$this->methods['locations_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
    
    //USUARIO =====================================================================================
    //=============================================================================================
    public function usuario_get()
    {
        $token = $this->uri->segment(3);
        if(empty($token))
        {
            $this->set_response(array(
                'status' => FALSE,
                'message' => 'Parâmetro inválido'
            ), REST_Controller::HTTP_NOT_FOUND);
            return;
        }

        $jwt = $token;     
        $key = "c7b34762-1284-4ea3-90a2-01f78e9761bf";

        try {
            $decoded = \Firebase\JWT\JWT::decode($jwt, $key, array('HS256'));
            $login = $decoded->login;
            $nome = $decoded->nome;
        } 
        catch (\Firebase\JWT\SignatureInvalidException $e) {
            $this->set_response(array(
                'status' => FALSE,
                'message' => 'Signature error'
            ), REST_Controller::HTTP_BAD_REQUEST); // HTTP_BAD_REQUEST (400) being the HTTP response code
            return; 
        }
        catch (\Firebase\JWT\BeforeValidException $e) {
            $this->set_response(array(
                'status' => FALSE,
                'message' => 'Before valid Exception'
            ), REST_Controller::HTTP_BAD_REQUEST); // HTTP_BAD_REQUEST (400) being the HTTP response code
            return;
        }
        catch (\Firebase\JWT\ExpiredException $e) {
            $this->set_response(array(
                'status' => FALSE,
                'message' => 'Expired Token'
            ), REST_Controller::HTTP_BAD_REQUEST); // HTTP_BAD_REQUEST (400) being the HTTP response code
            return;
        }
        catch(Exception $e) {
            $this->set_response(array(
                'status' => FALSE,
                'message' => 'Parâmetro inválido'
            ), REST_Controller::HTTP_NOT_FOUND);
            return;
        }

        //verify token
        if ($login === NULL)
        {
            $this->set_response(array(
                'status' => FALSE,
                'message' => 'Parâmetro inválido'
            ), REST_Controller::HTTP_NOT_FOUND);
            return;
        }

        $result = $this->model_login->login($login);
        if(!$result)
        {
            $this->set_response(array(
                'status' => FALSE,
                'message' => 'Usuário não encontrado'
            ), REST_Controller::HTTP_NOT_FOUND);
            return;
        }
        
        $id = (int)$result[0]->id;
        $dados = array(
            "login" => $login,
            "nome"  => $nome,
            "id"    => $id
        );
        
        $this->set_response($dados, REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code 
    }

     
    		

    

    public function verificaEstoque_post()
    {   
        $id_centrocusto = $this->post('id_centro_custo');
        $cod_produto = $this->post('cod_produto');

        if(!is_numeric($id_centrocusto))
        {
            $this->set_response('Ops! Não foi possível encontrar estoque!', REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $result = $this->model_produto->getProduto(null, $id_centrocusto, $cod_produto);

        if(!$result)
        {
            $this->set_response('Ops! Item não encontrado!', REST_Controller::HTTP_NOT_FOUND);
            return;
        }

        
        $this->set_response($result[0], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code 
    }

    public function registraSaida_post()
    {   

        $id_centrocusto = $this->post('id_centro_custo');
        $produto_id = $this->post('id_produto');
        $cod_produto = $this->post('cod_produto');
        $qtd_saida = $this->post('qtd_saida'); 



        $qtd_estoque = 0;

        $result = $this->model_produto->getByProduto($cod_produto, $id_centrocusto);
        if($result)
        {
            $produto_id =  $this->dados['alterar']['id'] = $result[0]->id;
        }  
        //  var_dump($produto_id);
        //  die;
            $data_movimento = date('d/m/Y H:i:s');
            $result = $this->model_estoque->getByEstoque($produto_id, $id_centrocusto);
            if($result){
                $id_estoque =  $this->dados['alterar']['id'] = $result[0]->id;
                $qtd_estoque = $this->dados['alterar']['quantidade'] = $result[0]->quantidade;
                $qtd_estoque = $qtd_estoque - $qtd_saida;

                $dados['quantidade'] = $qtd_estoque;
                $dados['id_produto'] = $produto_id;
                $dados['ultima_alteracao'] = $data_movimento;
                $result = $this->model_estoque->atualizarEstoque($id_estoque, $id_centrocusto, $dados);

                 $id_user = $this->post('id_user');
                 $matricula = $this->post('pessoa');

               
               $data['id_produto'] = $produto_id;
              
               $data['data_movimento'] = $data_movimento;
              
        
                $doc = $this->input->post('nro_documento');
                if ($doc == "")
                      $doc = 0;
        
                $data['nro_documento'] = $doc;
                $data['observacao'] = "2APITeste0612";
                $data['id_usuario'] = $id_user;
                $data['flag_mov']  = 0;
                $data['tipo_mov']  = 0;
                $data['valor_unitario'] = 0;
                $data['valor_entrada'] = 0;
                $data['quantidade'] = $qtd_saida;
                $data['matricula_servidor'] = $matricula;
               // atualizando estoque 
              
                $result = $this->model_movimento->cadastrarMovimento($data);

            //     if($result){
            //         $message = array('message_heading' => 'Estoque Movimento estoque cadastrado com sucesso!','class_result'  => 'green');
            //         $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
            //         redirect(base_url('/EntradaSaida'));
            //     }else{
    
            //         $message = array('message_heading' => 'Erro ao cadastrar Movimento Estoque!','class_result'  => 'red');
            //         $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
            //         redirect(base_url('/EntradaSaida'));
            //     }



            
                $this->set_response($dados, REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            }
       
    }


    public function base64url_encode($data) { 
      return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
    } 

    public function base64url_decode($data) { 
      return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
    }

}
