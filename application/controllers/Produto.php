<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produto extends ES_Controller
{


    private $id_user;
    private $user_matricula;
    private $id_centro_custo;

    protected $dbservidor;

    function __construct()
    {
        parent::__construct();
        $this->logado();

        $CI = &get_instance();

        //   print_r($qry->result());

        // carregar todas as Models que serão utilizadas

        $this->load->model('model_servidor');

        $this->load->model('model_estoque');

        $this->load->model('model_movimento');
        $this->load->model('model_produto');
        $this->load->model('model_tipoprod');
        $this->load->model('model_nivel');

        $this->id_user = $this->session->userdata['logged_in']['id'];
        $this->id_nivel = $this->session->userdata['logged_in']['nivel'];
        $this->user_matricula = $this->session->userdata['logged_in']['matricula'];
        $this->id_centro_custo = $this->session->userdata['logged_in']['id_centro_custo'];
        $this->data['tipo_estoque'] = $this->session->userdata['logged_in']['tipo_estoque'];

        $this->data['centro_custo'] = $this->id_centro_custo = $this->session->userdata['logged_in']['id_centro_custo'];

        $this->data['qtd_minimos'] = $this->last_qtd_minimos('id_centro_custo');
        return array();
    }

    function index()
    {
        $this->data['unidades'] = $this->getUnidades();
        $this->data['tipoprodutos'] = $this->getTipoProdutos();
        $produtos = $this->getProdutos();

        foreach ($produtos as $prod) {
            $fotos = $this->model_produto->getFotos($prod->id);
            $fotos = ($fotos) ? $fotos : array();

            $file_name = array();
            foreach ($fotos as $foto) {
                $file_name[] = $foto->path;
            }
            $prod->file_name = $file_name;
        }

        $this->data['produtos'] = $produtos;
        $this->load->view('produto/cadastrar', $this->data);
    }

    function getProdutos()
    {

        $produtos = $this->model_produto->getProdutos($this->id_centro_custo);
        if ($produtos)
            return $produtos;

        return array();
    }

    function getTipoProdutos()
    {

        $tipoprodutos = $this->model_tipoprod->getTipoProdutos($this->id_centro_custo);
        if ($tipoprodutos)
            return $tipoprodutos;

        return array();
    }

    function getUnidades()
    {
        $unidades = $this->model_produto->getUnidadesMedidas();
        if ($unidades)
            return $unidades;

        return array();
    }

    function getProduto()
    {
        //AUTENTICAR
        //    $this->auth->check_logged($this->router->class , $this->router->method);

        if (!is_numeric($this->uri->segment(3))) {
            $message = array('message_heading' => 'Parâmetro inválido!', 'class_result'  => 'red');
            $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
            redirect(base_url('/produto'));
        }

        $id_produto = $this->uri->segment(3);
        return $this->model_produto->getProduto($id_produto, $this->id_centro_custo);
    }



    function cadastrar()
    {
        //AUTENTICAR
        //        $this->auth->check_logged($this->router->class , $this->router->method);

        $this->form_validation->set_rules('nome', 'Nome Produto', 'trim|required');
        $this->form_validation->set_rules('cod_produto', 'Codigo Produto', 'trim|required');
        $this->form_validation->set_rules('tipoprodutos[]', 'Tipo Produto', 'trim|required|numeric');
        $this->form_validation->set_rules('unidades', 'Unidade', 'trim|required|numeric');
        $this->form_validation->set_rules('qtd_minima', 'Quantidade Minima', 'trim|required|numeric');
        $this->form_validation->set_rules('qtd_maxima', 'Quantidade Maxima', 'trim|required|numeric');
        $this->form_validation->set_rules('observacao', 'Observacao', 'trim');

        if ($this->form_validation->run() == FALSE) {
            $message = array('message_heading' => validation_errors(), 'class_result'  => 'red');
            $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
            redirect(base_url('/Produto'));
        }

        $data_cadastro = date('d/m/Y H:i:s');
        $data['data_cadastro'] = $data_cadastro;
        $data['cod_produto'] = $this->input->post("cod_produto");
        $data['nome'] = $this->input->post("nome");
        //  $data['id_tipoproduto'] = $this->input->post("tipoprodutos");
        $data['id_unidade'] = $this->input->post("unidades");
        $data['qtd_minima'] = $this->input->post("qtd_minima");
        $data['qtd_maxima'] = $this->input->post("qtd_maxima");
        $data['observacao'] = $this->input->post("observacao");
        $data['id_centro_custo'] = $this->id_centro_custo;
        $data['excluido'] = 0;
        $data['ativo'] = 1;





        $result = $this->model_produto->getByProduto($data['cod_produto'], $this->id_centro_custo);
        if (!$result) {
            // var_dump($data);
            // var_dump($result);

            // die;
            $result = $this->model_produto->cadastrarProduto($data, $this->id_centro_custo);

            if ($result) {
                $id_tipo_produto = $this->input->post("tipoprodutos");
                $this->data['tipoprodutos'] = $this->getTipoProdutos();

                foreach ($id_tipo_produto as $id) {

                    $cad['id_tipo_produto'] = $id;
                    $cad['id_produto'] = $result;
                    $this->model_produto->cadastrarTipoProduto($cad);
                }
                $this->load->library('upload');
                $files = $_FILES;
                $cpt = count($_FILES['userfile']['name']);
                if ($cpt <= 3) {
                    for ($i = 0; $i < $cpt; $i++) {
                        $_FILES['userfile']['name'] = strtr(utf8_decode($files['userfile']['name'][$i]), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');

                        $_FILES['userfile']['type'] = $files['userfile']['type'][$i];
                        $_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$i];
                        $_FILES['userfile']['error'] = $files['userfile']['error'][$i];
                        $_FILES['userfile']['size'] = $files['userfile']['size'][$i];

                        move_uploaded_file($files['userfile']['tmp_name'][$i], 'uploads/' . $data['nome'] . '_' . $files['userfile']['name'][$i]);
                        if (file_exists('uploads/' . $data['nome'] . '_' . $files['userfile']['name'][$i])) {

                            $this->file['id_produto'] = $result;
                            $this->file['path'] = $data['nome'] . '_' . $files['userfile']['name'][$i];
                            $this->file['excluido'] = 0;

                            // var_dump($files['image']['name']);
                            // die;

                            $this->model_produto->cadastrarFoto($this->file);
                        }
                    }
                } else {
                    $message = array('message_heading' => 'Você excedeu o limite de imagens!', 'class_result'  => 'red');
                    $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
                    redirect(base_url('/Produto'));
                }


                // foreach ($files as $img) {

                //     move_uploaded_file($img, '../../uploads/empresa_1/logo' . $img['name']);
                // }


                // foreach ($files['userfile']['name'] as $value) {
                //     $this->file['id_produto'] = $result;
                //     $this->file['path'] = $data['nome'] . '_' . $value;
                //     $this->file['excluido'] = 0;

                //     // var_dump($files['image']['name']);
                //     // die;

                //     $this->model_produto->cadastrarFoto($this->file);
                // }

                $message = array('message_heading' => 'Produto  cadastrado com sucesso!', 'class_result'  => 'green');
                $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
                redirect(base_url('/Produto'));
            }

            $message = array('message_heading' => 'Erro ao cadastrar Produto!', 'class_result'  => 'red');
            $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
            redirect(base_url('/Produto'));
        }

        $message = array('message_heading' => 'Ops! Codigo do Produto já existente!', 'class_result'  => 'red');
        $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
        redirect(base_url('/Produto'));
    }



    function editar()
    {
        //AUTENTICAR
        //  $this->auth->check_logged($this->router->class , $this->router->method);

        if (!is_numeric($this->uri->segment(3))) {
            $message = array('message_heading' => 'Parâmetro inválido!', 'class_result'  => 'red');
            $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
            redirect(base_url('/Produto'));
        }


        $id_produto = $this->uri->segment(3);

        $result = $this->model_produto->getProduto($id_produto, $this->id_centro_custo, $cod_produto = NULL);
        if ($result) {
            $this->data['alterar']['id'] = $result[0]->id;
            $this->data['alterar']['cod_produto'] = $result[0]->cod_produto;
            $this->data['alterar']['qtd_estoque'] = $result[0]->qtd_estoque;
            $this->data['alterar']['nome_produto'] = $result[0]->nome_produto;
            $this->data['alterar']['id_tipo_produto'] = $result[0]->id_tipo_produto;
            $this->data['alterar']['id_unidade'] = $result[0]->id_unidade;
            $this->data['alterar']['qtd_minima'] = $result[0]->qtd_minima;
            $this->data['alterar']['qtd_maxima'] = $result[0]->qtd_maxima;
            $this->data['alterar']['observacao'] = $result[0]->observacao;
            $this->data['alterar']['fotos'] = $this->model_produto->getFotos($id_produto);
        }

        $this->data['unidades'] = $this->getUnidades();
        $this->data['tipos_produtos'] = $this->getTipoProdutos();
        $this->data['produto'] = $this->getProduto();
        $this->load->view('/produto/editar', $this->data);
        $this->data['fotos'] = $this->model_produto->getFotos($id_produto);
    }

    function excluirAnexo()
    {
        //TODO: MÉTODO NO MODEL 
        $id_arquivo = $this->input->post('id_arquivo');
        $id_produto = $this->input->post('id_produto');

        $result = $this->model_produto->deletarAnexo($id_arquivo);
        // var_dump($id_arquivo);


        if ($result) {

            $message = array('message_heading' => 'Anexo excluído com sucesso!', 'class_result'  => 'green');
            $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
            redirect(base_url('/Produto/editar/' . $id_produto));
        }

        $message = array('message_heading' => 'Erro ao excluir anexo!', 'class_result'  => 'red');
        $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
        redirect(base_url('/Produto/editar/' . $id_produto));
    }


    function alterar()
    {

        // INSERIR AQUI OS CAMPOS OBRIGATÓRIOS DO FORMULÁRIO PARA VALIDAÇÃO
        $this->form_validation->set_rules('id', 'ID Produto', 'trim|required|numeric');
        $this->form_validation->set_rules('nome_produto', 'Nome do Produto', 'trim|required');
        $this->form_validation->set_rules('cod_produto', 'Codigo Produto', 'trim|required');
        $this->form_validation->set_rules('unidade', 'ID Unidade', 'trim|required|numeric');
        //   $this->form_validation->set_rules('tipoproduto', 'ID Tipo Produto', 'trim|required|numeric');
        $this->form_validation->set_rules('qtd_minima', 'Quantidade Minima', 'trim|required|numeric');
        $this->form_validation->set_rules('qtd_maxima', 'Quantidade Maxima', 'trim|required|numeric');
        $this->form_validation->set_rules('observacao', 'Observaca', 'trim');
        if ($this->form_validation->run() == FALSE) {
            $message = array('message_heading' => validation_errors(), 'class_result'  => 'red');
            $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
            redirect(base_url('/Produto'));
        }

        // INSERIR AQUI OS DEMAIS CAMPOS DO FORMULÁRIO - RECEBE POR POST
        $id_produto = $this->input->post('id');
        $data['nome'] = $this->input->post('nome_produto');
        $data['cod_produto'] = $this->input->post('cod_produto');

        $data['id_unidade'] = ($this->input->post('unidade')) ? $this->input->post('unidade') : null;
        // $data['id_tipoproduto'] =  0;

        $data['qtd_minima'] = $this->input->post('qtd_minima');
        $data['qtd_maxima'] = $this->input->post('qtd_maxima');
        $data['observacao'] = $this->input->post('observacao');
        $data['id_centro_custo'] = $this->id_centro_custo;
        $data['excluido'] = 0;
        $data['fixo'] = 0;
        $data['ativo'] = 1;


        //salva os tipos produtos
        $result = $this->model_produto->alterarProduto($id_produto, $this->id_centro_custo, $data);
        if ($result) {
            $id_tipoproduto = $this->model_produto->getTipoProdutos($id_produto);
            $id_tipo_produto = $this->input->post("tipoprodutos");

            $count = sizeof($id_tipo_produto);


            for ($i = 0; $i < $count; $i++) {

                //$infoP['id'] = $id->id;
                $infoP['id_tipo_produto'] = $id_tipo_produto[$i];
                $infoP['id_produto'] = $id_produto;
                $this->model_produto->alterarTipoProduto($id_tipoproduto[$i]->id, $id_produto, $infoP);
            }



            $countFile = $this->model_produto->countFile($id_produto);
            //upload arquivos
            $this->load->library('upload');
            $files = $_FILES;
            $cpt = count($_FILES['userfile']['name']);
            $countFiles = sizeof($countFile);
            // var_dump($countFile);
            // die;
            if ($countFiles < 3) {

                if ($cpt <= 3) {
                    for ($i = 0; $i < $cpt; $i++) {
                        $_FILES['userfile']['name'] = strtr(utf8_decode($files['userfile']['name'][$i]), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');

                        $_FILES['userfile']['type'] = $files['userfile']['type'][$i];
                        $_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$i];
                        $_FILES['userfile']['error'] = $files['userfile']['error'][$i];
                        $_FILES['userfile']['size'] = $files['userfile']['size'][$i];

                        move_uploaded_file($files['userfile']['tmp_name'][$i], 'uploads/' . $data['nome'] . '_' . $files['userfile']['name'][$i]);
                        if (file_exists('uploads/' . $data['nome'] . '_' . $files['userfile']['name'][$i])) {

                            $this->file['id_produto'] = $id_produto;
                            $this->file['path'] = $data['nome'] . '_' . $files['userfile']['name'][$i];
                            $this->file['excluido'] = 0;

                            // var_dump($files['image']['name']);
                            // die;

                            $this->model_produto->cadastrarFoto($this->file);
                        }
                    }
                } else {
                    $message = array('message_heading' => 'Você excedeu o limite de imagens!', 'class_result'  => 'red');
                    $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
                    redirect(base_url('/Produto/editar/' . $id_produto));
                }
            } else {
                $message = array('message_heading' => 'Você já tem 3 arquivos anexados!', 'class_result'  => 'red');
                $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
                redirect(base_url('/Produto/editar/' . $id_produto));
            }
            //salva na tabela das fotos
            // foreach ($files['userfile']['name'] as $value) {
            //     $this->file['id_produto'] = $id_produto;
            //     $this->file['path'] = $data['nome'] . '_' . $value;
            //     $this->file['excluido'] = 0;

            //     // var_dump($files['image']['name']);
            //     // die;

            //     $this->model_produto->cadastrarFoto($this->file);
            // }


            $message = array('message_heading' => 'Produto alterado com sucesso!', 'class_result'  => 'green');
            $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
            redirect(base_url('/produto'));
        }

        $message = array('message_heading' => 'Erro ao alterar Produto!', 'class_result'  => 'red');
        $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));

        redirect(base_url('/produto'));
    }

    function deletar()
    {
        $id_produto = $this->input->post('id_produto');

        $result = $this->model_produto->validarExclusao($id_produto, $this->id_centro_custo);
        if (!$result) {
            $result_delete = $this->model_produto->deletarProduto($id_produto);
            if ($result_delete) {

                $message = array('message_heading' => 'Produto excluído com sucesso!', 'class_result'  => 'green');
                $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
                redirect(base_url('/produto'));
            }

            $message = array('message_heading' => 'Erro ao excluir produto!', 'class_result'  => 'red');
            $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
            redirect(base_url('/produto'));
        } else {
            $message = array('message_heading' => 'Não é possível deletar produtos que já tiveram movimentos!', 'class_result'  => 'red');
            $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
            redirect(base_url('/produto'));
        }
    }
}
