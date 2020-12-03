<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TipoProd extends ES_Controller
{

    private $id_user;
    private $id_centro_custo;
    private $user_matricula;

    function __construct()
    {
        parent::__construct();
        $this->logado();

        // carregar todas as Models que serão utilizadas
        $this->load->model('model_estoque');
        $this->load->model('model_produto');
        $this->load->model('model_tipoprod');
        $this->load->model('model_nivel');

        $this->id_user = $this->session->userdata['logged_in']['id'];
        $this->id_nivel = $this->session->userdata['logged_in']['nivel'];
        $this->user_matricula = $this->session->userdata['logged_in']['matricula'];
        $this->id_centro_custo = $this->session->userdata['logged_in']['id_centro_custo'];
        $this->data['qtd_minimos'] = $this->last_qtd_minimos();
        $this->data['tipo_estoque'] = $this->session->userdata['logged_in']['tipo_estoque'];
        return array();
    }

    function index()
    {
        //AUTENTICAR
        //     $this->auth->check_logged($this->router->class , $this->router->method);

        $this->data['tipos'] = $this->consultar();
        $this->load->view('/tipoProd/tipoProd', $this->data);
    }

    function consultar()
    {
        $tipos = $this->model_tipoprod->getTipoProdutos($this->id_centro_custo);
        if ($tipos)
            return $tipos;

        return array();
    }

    function cadastrar()
    {
        //AUTENTICAR
        //        $this->auth->check_logged($this->router->class , $this->router->method);

        $this->form_validation->set_rules('nome', 'nome', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $message = array('message_heading' => validation_errors(), 'class_result'  => 'red');
            $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
            redirect(base_url('/tipoProd'));
        }

        $data['nome'] = mb_strtoupper($this->input->post('nome'), mb_internal_encoding());
        $data['id_centro_custo'] = $this->id_centro_custo;
        $data['ativo'] = 1;

        $result = $this->model_tipoprod->getByName($data['nome'], $this->id_centro_custo);
        if (!$result) {

            $result = $this->model_tipoprod->cadastrarTipoProduto($data, $this->id_centro_custo);
            if ($result) {
                $message = array('message_heading' => 'Tipo cadastrado com sucesso!', 'class_result'  => 'green');
                $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
                redirect(base_url('/tipoProd'));
            }

            $message = array('message_heading' => 'Erro ao cadastrar Tipo produto!', 'class_result'  => 'red');
            $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
            redirect(base_url('/tipoProd'));
        }

        $message = array('message_heading' => 'Ops! Tipo Produto já existente!', 'class_result'  => 'red');
        $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
        redirect(base_url('/tipoProd'));
    }


    function editar()
    {
        //AUTENTICAR
        //      $this->auth->check_logged($this->router->class , $this->router->method);

        if (!is_numeric($this->uri->segment(3))) {
            $message = array('message_heading' => 'Parâmetro inválido!', 'class_result'  => 'red');
            $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
            redirect(base_url('/tipoProd'));
        }

        $id_tipoproduto = $this->uri->segment(3);
        $result = $this->model_tipoprod->getTipoProduto($id_tipoproduto, $this->id_centro_custo);
        if ($result) {
            $this->data['alterar']['id'] = $result[0]->id;
            $this->data['alterar']['nome'] = $result[0]->nome;
        }

        $this->data['tipos'] = $this->consultar();
        $this->load->view('/tipoProd/tipoProd', $this->data);
    }


    function alterar()
    {
        //AUTENTICAR
        //      $this->auth->check_logged($this->router->class , $this->router->method);

        $this->form_validation->set_rules('nome', 'Nome', 'trim|required');
        $this->form_validation->set_rules('id_tipoproduto', 'ID Tipo Produto', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $message = array('message_heading' => validation_errors(), 'class_result'  => 'red');
            $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
            redirect(base_url('/tipoProd'));
        }

        $id_tipoproduto = $this->input->post('id_tipoproduto');
        $data['nome'] = mb_strtoupper($this->input->post('nome'), mb_internal_encoding());

        $result = $this->model_tipoprod->getByName($data['nome'], $this->id_centro_custo);
        if (!$result) {

            $result = $this->model_tipoprod->alterarTipoProduto($id_tipoproduto, $this->id_centro_custo, $data);
            if ($result) {
                $message = array('message_heading' => 'Tipo Produto alterado com sucesso!', 'class_result'  => 'green');
                $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
                redirect(base_url('/tipoProd'));
            }

            $message = array('message_heading' => 'Erro ao alterado Tipo Produto!', 'class_result'  => 'red');
            $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
            redirect(base_url('/tipoProd'));
        }

        $message = array('message_heading' => 'Ops! Nome do Tipo Produto já existente!', 'class_result'  => 'red');
        $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
        redirect(base_url('/tipoProd'));
    }


    function deletar()
    {
        //AUTENTICAR
        //       $this->auth->check_logged($this->router->class , $this->router->method);

        $this->form_validation->set_rules('id_tipoproduto', 'ID tipo produto', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $message = array('message_heading' => validation_errors(), 'class_result'  => 'red');
            $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
            redirect(base_url('/tipoProd'));
        }

        $id_tipoproduto = $this->input->post('id_tipoproduto');
        $result = $this->model_tipoprod->validarExclusao($id_tipoproduto, $this->id_centro_custo);
        if ($result) {



            $message = array('message_heading' => 'Não é possível deletar tipo produto que esteve ou está em uso!', 'class_result'  => 'red');
            $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
            redirect(base_url('/tipoProd'));
        } else {
            if ($this->model_tipoprod->deletarTipoProduto($id_tipoproduto)) {
                $message = array('message_heading' => 'Tipo produto deletado com sucesso!', 'class_result'  => 'green');
                $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
                redirect(base_url('/tipoProd'));
            }
        }

        $message = array('message_heading' => 'Não foi possível deletar tipo produto. Verifique se há relação com modelos!', 'class_result'  => 'red');
        $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
        redirect(base_url('/tipoProd'));
    }
}
