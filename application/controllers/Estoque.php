<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Estoque extends ES_Controller
{

    private $id_user;
    private $id_centro_custo;
    private $user_matricula;
    private $user_nome;

    function __construct()
    {
        parent::__construct();
        $this->logado();

        // carregar todas as Models que serão utilizadas
        $this->load->model('model_servidor');
        $this->load->model('model_estoque');
        $this->load->model('model_movimento');
        $this->load->model('model_produto');
        $this->load->model('model_tipoprod');
        $this->load->model('model_nivel');

        $this->id_user = $this->session->userdata['logged_in']['id'];
        $this->id_nivel = $this->session->userdata['logged_in']['nivel'];
        $this->user_nome = $this->session->userdata['logged_in']['nome'];
        $this->user_matricula = $this->session->userdata['logged_in']['matricula'];
        $this->id_centro_custo = $this->session->userdata['logged_in']['id_centro_custo'];
        $this->data['tipo_estoque'] = $this->session->userdata['logged_in']['tipo_estoque'];

        $this->data['qtd_minimos'] = $this->last_qtd_minimos();
        return array();
    }

    function index()
    {
        $this->data['unidades'] = $this->getUnidades();
        $this->data['tipoprodutos'] = $this->getTipoProdutos();
        $this->data['produtos'] = $this->getProdutos();
        $this->data['estoques'] = $this->consultarEstoque();
        $this->data['entradas'] = $this->GetTipoEntradas();
        $this->data['saidas'] = $this->GetTipoSaidas();
        $this->data['total_estoque'] = $this->model_estoque->getTotalEstoqueCentroCusto($this->id_centro_custo);
        $this->data['total_entrada_mes'] = $this->model_estoque->getTotalEntradaMes($this->id_centro_custo);
        $this->data['total_saida_mes'] = $this->model_estoque->getTotalSaidaMes($this->id_centro_custo);
        $this->data['estoques'] = $this->consultar();
        $this->load->view('/estoque/estoque', $this->data);
    }

    function consultar()
    {

        $estoques = $this->model_estoque->getEstoques($this->id_centro_custo);
        if ($estoques) {

            $x  = 0;

            foreach ($estoques as $value) {
                $Qestoque = $estoques[$x]->qtd_estoque;
                $Qminima = $estoques[$x]->qtd_minima;

                $valor = $Qestoque - $Qminima;
                $value->diferenca = $valor;

                $value->minimo = NULL;

                if ($Qminima >= $Qestoque) {
                    $value->minimo = "S";
                }
                $x++;
            }

            // var_dump($estoques);
            // die;

            return $estoques;
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
        // $flag_mov = '1';
        // $entradas = $this->model_movimento->getMovimentos($this->id_centro_custo, $flag_mov);
        // if ($entradas) {

        //     $x  = 0;
        //     foreach ($entradas as $value) {

        //         $matricula = $entradas[$x]->matricula_servidor;

        //         $result = $this->model_servidor->getServidores($matricula);
        //         if ($result) {
        //             $nome = $result[0]->nome;
        //             $value->nome_servidor = $nome;
        //         }

        //         $x++;
        //     }
        //     return $entradas;
        // }
        // return array();



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
        // $flag_mov = '0';
        // $saidas = $this->model_movimento->getMovimentos($this->id_centro_custo, $flag_mov);
        // if ($saidas) {
        //     $x  = 0;

        //     foreach ($saidas as $value) {

        //         $matricula = $saidas[$x]->matricula_servidor;

        //         $result = $this->model_servidor->getServidores($matricula);
        //         if ($result) {
        //             $nome = $result[0]->nome;
        //             $value->nome_servidor = $nome;
        //         }

        //         $x++;
        //     }

        //     return $saidas;
        // }

        // return array();

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

    function getProdutos()
    {
        $produtos = $this->model_produto->getProdutos($this->id_centro_custo);
        if ($produtos)
            return $produtos;

        return array();
    }
}
