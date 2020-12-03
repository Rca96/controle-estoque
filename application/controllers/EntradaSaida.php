<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EntradaSaida extends ES_Controller
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
        $this->load->model('model_tipomotivo');

        $this->id_user = $this->session->userdata['logged_in']['id'];
        $this->id_nivel = $this->session->userdata['logged_in']['nivel'];
        $this->user_matricula = $this->session->userdata['logged_in']['matricula'];
        $this->id_centro_custo = $this->session->userdata['logged_in']['id_centro_custo'];
        $this->data['tipo_estoque'] = $this->session->userdata['logged_in']['tipo_estoque'];

        // $this->id_local = $this->session->userdata['logged_in']['id_local'];

        $this->data['qtd_minimos'] = $this->last_qtd_minimos('id_centro_custo');
    }

    function index()
    {
        //AUTENTICAR
        //     $this->auth->check_logged($this->router->class , $this->router->method);

        $this->data['unidades'] = $this->getUnidades();

        $this->data['tipoprodutos'] = $this->getTipoProdutos();
        $this->data['produtos'] = $this->getProdutos();
        $this->data['motivos'] = $this->getMotivos();

        $this->data['estoques'] = $this->consultarEstoque();
        $this->data['entradas'] = $this->GetTipoEntradas();
        $this->data['saidas'] = $this->GetTipoSaidas();
        $this->data['last_id_ficha'] = $this->getIdFicha();

        $this->load->view('/entradasaida/movimento', $this->data);
    }

    function getServidoresJson()
    {
        $servidores = $this->model_servidor->getServidoresToJson();
        if ($servidores) {
            $data = array();
            foreach ($servidores as $value) {
                $data[] = array("matricula" => $value->matricula, "name" => $value->nome);
            }
            echo json_encode($data);
        }

        return array();
    }


    function consultarEstoque()
    {
        $estoques = $this->model_estoque->getEstoques($this->id_centro_custo);
        if ($estoques)
            return $estoques;

        return array();
    }


    function getIdFicha()
    {
        $id = $this->model_movimento->getFicha();;
        if ($id)
            return $id;

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

    function getTipoEntradas()
    {
        $flag_mov = '1';
        $entradas = $this->model_movimento->getMovimentos($this->id_centro_custo, $flag_mov);
        if ($entradas)
            foreach ($entradas as $entrada) {
                $res = $this->model_movimento->getFichaConteudo($entrada->id);
                $res = ($res) ? $res : array();

                $entrada->fichas = $res;
            }
        return $entradas;

        return array();
    }

    function getTipoSaidas()
    {
        $flag_mov = '0';
        $saidas = $this->model_movimento->getMovimentos($this->id_centro_custo, $flag_mov);
        if ($saidas) {
            foreach ($saidas as $saida) {
                $res = $this->model_movimento->getFichaConteudoSaida($saida->id);
                $res = ($res) ? $res : array();

                $saida->fichas = $res;
            }
        }
        return $saidas;

        return array();
    }

    function getFichas($id_mov)
    {

        $fichas = $this->model_movimento->getFichaConteudo($id_mov);
        if ($fichas)
            return $fichas;

        return array();
    }

    function getProdutos()
    {
        $produtos = $this->model_produto->getProdutos($this->id_centro_custo);
        if ($produtos) {
            return $produtos;
        }
        return array();
    }

    function getMotivos()
    {
        $motivos = $this->model_tipomotivo->getTipoMotivos($this->id_centro_custo);
        if ($motivos) {
            return $motivos;
        }
        return array();
    }


    function getVerEstoque()
    {
        $estoque = 0;
        if ($this->input->post('id_produto')) {
            $id_produto = $this->input->post('id_produto');

            $result = $this->model_produto->getProduto($id_produto, $this->id_centro_custo);
            if ($result) {
                $estoque = $result[0]->qtd_estoque;
            }
        }
        echo $estoque;
    }


    function CadastrarMovimento()
    {
        //AUTENTICAR
        //        $this->auth->check_logged($this->router->class , $this->router->method);


        $this->form_validation->set_rules('nro_documento', 'Nro. Documento', 'trim');
        $this->form_validation->set_rules('id_produto', 'ID Produto', 'trim|required');

        $this->form_validation->set_rules('pessoa', 'Matricula Servidor ', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('solicitante', 'Nome do Servidor ', 'trim|xss_clean');
        $this->form_validation->set_rules('valor_entrada', 'Valor', 'trim');
        $this->form_validation->set_rules('valor_unitario', 'Valor Unitario', 'trim');
        $this->form_validation->set_rules('quantidade', 'Quantidade ', 'trim|required');
        $this->form_validation->set_rules('observacao', 'Observacao', 'trim');
        if ($this->input->post('rd_tipo') == 'rd_entrada')
            $tipo_mov = 1;
        else
            $tipo_mov = 0;

        if ($tipo_mov == 0) {
            $this->form_validation->set_rules('pessoa', 'matricula do Servidor', 'trim|numeric|required|xss_clean');
            $this->form_validation->set_rules('solicitante', 'Nome do Servidor', 'trim|required|xss_clean');
        }

        if ($this->form_validation->run() == FALSE) {
            $message = array('message_heading' => validation_errors(), 'class_result'  => 'red');
            $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
            redirect(base_url('/EntradaSaida'));
        }

        $quantidade = $data['quantidade'] = str_replace('', '.', $this->input->post('quantidade'));
        $quantidade = $data['quantidade'] = str_replace(',', '.', $this->input->post('quantidade'));

        $valor_entrada = $data['valor_entrada'] = str_replace('', '.', $this->input->post('valor_entrada'));
        $valor_entrada = $data['valor_entrada'] = str_replace(',', '.', $this->input->post('valor_entrada'));

        $valor_unitario = $data['valor_unitario'] = str_replace('', '.', $this->input->post('valor_unitario'));
        $valor_unitario = $data['valor_unitario'] = str_replace(',', '.', $this->input->post('valor_unitario'));

        if ($valor_unitario == "")  $valor_unitario = 0;
        if ($valor_entrada == "") $valor_entrada = 0;

        if ($this->input->post('rd_tipo') == 'rd_entrada')
            $tipo_mov = 1;
        else
            $tipo_mov = 0;


        $data_movimento = date('d/m/Y H:i:s');

        $produto_id =  $this->input->post("id_produto");

        $id_estoque = 0;
        $qtd_estoque = 0;

        $result = $this->model_estoque->getByEstoque($produto_id, $this->id_centro_custo);
        if ($result) {
            $id_estoque =  $this->dados['alterar']['id'] = $result[0]->id;
            $qtd_estoque = $this->dados['alterar']['quantidade'] = $result[0]->quantidade;
        }

        if ($tipo_mov == 1) {
            $qtd_estoque = $qtd_estoque + $quantidade;
        } else {
            $qtd_estoque = $qtd_estoque - $quantidade;
        }

        //----------------------------------------------


        // dados para movimento do estoque 
        $data['id_produto'] = $this->input->post("id_produto");
        if ($this->data['tipo_estoque'] == 1) {
            $data['id_motivo'] = $this->input->post("id_motivo");
        } else {
            $data['id_motivo'] = 0;
        }
        $data['data_movimento'] = $data_movimento;

        $doc = $this->input->post('nro_documento');
        if ($doc == "")
            $doc = 0;

        $data['nro_documento'] = $doc;

        $data['valor_entrada'] = $valor_entrada;

        $data['observacao'] = $this->input->post("observacao");
        $data['id_usuario'] = $this->id_user;
        $data['flag_mov']  = $tipo_mov;
        $data['tipo_mov']  = $tipo_mov;
        $data['valor_unitario'] = $valor_unitario;
        $data['quantidade'] = $quantidade;

        $matricula = $this->input->post('pessoa');


        if ($tipo_mov == 1) {
            $data['matricula_servidor'] =  $this->user_matricula;
        } else {
            $data['matricula_servidor'] = $matricula;
            $this->form_validation->set_rules('id_motivo', 'ID Motivo', 'trim|required');
            for ($i = 0; $i < $quantidade; $i++) {
                $id_ficha = $this->input->post('ficha_numero' . $i);

                if ($id_ficha != "") {
                    if ($this->data['tipo_estoque'] == 1) {
                        $verifica_ficha = $this->model_movimento->validaProdutoFicha($id_ficha, $data['id_produto']);
                        if (!$verifica_ficha) {
                            $message = array('message_heading' => 'Não foi possível realizar a saída! A ficha ' . $id_ficha . ' não existe ou não corresponde ao produto informado!', 'class_result'  => 'red');
                            $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
                            redirect(base_url('/EntradaSaida'));
                        } else {
                            $message = array('message_heading' => 'Não foi possível realizar a saída! Informe o número da ficha e o conteúdo!', 'class_result'  => 'red');
                            $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
                            redirect(base_url('/EntradaSaida'));
                        }
                    }
                }
            }
        }


        $result = $this->model_movimento->cadastrarMovimento($data);
        if ($result) {
            //verifica tipo_estoque
            if ($this->data['tipo_estoque'] == 1) {
                //verifica se é entrada
                if ($tipo_mov == 1) {
                    for ($i = 0; $i < $quantidade; $i++) {

                        $ficha['id_estoque_mov'] = $result;

                        $cad_ficha = $this->model_movimento->cadastrarFicha($ficha);
                    }
                } else {
                    //se é saída
                    for ($i = 0; $i < $quantidade; $i++) {
                        $id = $this->input->post('ficha_numero' . $i);;
                        $ficha['conteudo'] = $this->input->post('Ficha' . $i);;
                        $ficha['retirado'] = 1;
                        $ficha['id_estoque_mov_saida'] = $result;

                        $update_ficha = $this->model_movimento->updateFicha($id, $ficha);

                        $verifica_ficha = $this->model_movimento->validaProdutoFicha($id, $data['id_produto'], $result);
                    }
                }
            }




            // dados para estoque
            $dados['quantidade'] = $qtd_estoque;
            $dados['id_produto'] = $produto_id;
            $dados['ultima_alteracao'] = $data_movimento;
            $dados['id_mov'] = $result;
            // atualizando estoque 

            if ($id_estoque > 0) {
                $result = $this->model_estoque->atualizarEstoque($id_estoque, $this->id_centro_custo, $dados);
            } else
                $result = $this->model_estoque->cadastrarEstoque($dados);

            if ($result) {
                $message = array('message_heading' => 'EstoqueMovimento estoque cadastrado com sucesso!', 'class_result'  => 'green');
                $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
                redirect(base_url('/EntradaSaida'));
            } else {

                $message = array('message_heading' => 'Erro ao cadastrar Movimento Estoque!', 'class_result'  => 'red');
                $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
                redirect(base_url('/EntradaSaida'));
            }
        }


        $message = array('message_heading' => 'Ops! Movimento Estoque já existente!', 'class_result'  => 'red');
        $this->session->set_flashdata('result', $this->parser->parse('template/result_message.php', $message));
        redirect(base_url('/EntradaSaida'));
    }
}
