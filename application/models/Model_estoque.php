<?php
class Model_Estoque extends CI_Model
{

    function getEstoques($id_centro_custo)
    {
        $this->db->select('e.*, e.quantidade as qtd_estoque, p.nome as nome_produto, p.cod_produto,
         um.abreviacao as unidade, tp.nome as nome_tipo, p.qtd_maxima, p.qtd_minima');
        $this->db->from('estoque e');
        $this->db->join('produtos p', 'p.id = e.id_produto');
        $this->db->join('unidade_medida um', 'um.id = p.id_unidade', 'LEFT');
        $this->db->join('tipo_produto tp', 'p.id_tipoproduto = tp.id', 'LEFT');
        // $this->db->join('estoque_mov em', 'e.id_mov = em.id', 'LEFT');
        //$this->db->join('motivo m', 'em.id_motivo = m.id', 'LEFT');
        $this->db->where('p.id_centro_custo', $id_centro_custo);
        $this->db->order_by("p.nome", "ASC");

        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        }
        return false;
    }

    function getEstoque($id_estoque, $id_centro_custo)
    {

        $this->db->select('e.*, e.quantidade as qtd_estoque, p.nome as nome_produto, p.cod_produto, um.abreviacao as unidade, tp.nome as nome_tipo, p.qtd_maxima, p.qtd_minima');
        $this->db->from('estoque e');
        $this->db->join('produto p', 'p.id = e.id_produto');
        $this->db->join('unidade_medida um', 'um.id = p.id_unidade', 'LEFT');
        $this->db->join('tipo_produto tp', 'p.id_tipoproduto = tp.id', 'LEFT');
        $this->db->where('e.id', $id_estoque);
        $this->db->where('p.id_centro_custo', $id_centro_custo);
        $query = $this->db->get();

        if ($query->num_rows() >= 1) {
            return $query->result();
        }
        return false;
    }

    function getByName($nome, $id_centro_custo)
    {
        $this->db->select('e.*, p.nome as nome_produto');
        $this->db->from("estoque e");
        $this->db->join('produto p', 'p.id = e.id_produto');
        $this->db->where('p.nome', $nome);
        $this->db->where('p.id_centro_custo', $id_centro_custo);

        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        }
        return false;
    }
    function getByEstoque($id_produto, $id_centro_custo)
    {
        $this->db->select('e.*, e.id, e.quantidade');
        $this->db->from("estoque e");
        $this->db->join('produtos p', 'p.id = e.id_produto');
        $this->db->where('e.id_produto', $id_produto);
        $this->db->where('p.id_centro_custo', $id_centro_custo);

        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        }
        return false;
    }


    function getMinimo($id_centro_custo)
    {
        $this->db->select('
            e.*,
            p.id as idproduto,
            e.quantidade as qtd_estoque,
            p.nome as nome_produto,
            p.cod_produto,
            um.abreviacao as unidade,
            tp.nome as nome_tipo,
            p.qtd_maxima,
            p.qtd_minima');

        $this->db->from('estoque e');
        $this->db->join('produtos p', '(p.id = e.id_produto) AND (p.qtd_minima >= e.quantidade)', 'LEFT');
        $this->db->join('unidade_medida um', 'um.id = p.id_unidade', 'LEFT');
        $this->db->join('tipo_produto tp', 'p.id_tipoproduto = tp.id', 'LEFT');

        $this->db->where('p.id_centro_custo', $id_centro_custo);

        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        }
        return false;
    }


    function cadastrarEstoque($data)
    {
        if ($this->db->insert('estoque', $data)) {
            $last_id = $this->db->insert_id();
            $this->logger->logAction('estoque create', (array) $data);
            return $last_id;
        }

        return false;
    }

    function alterarEstoque($id_estoque, $id_centro_custo, $data)
    {
        $this->db->where('id', $id_estoque);
        if ($this->db->update('estoque', $data)) {
            $this->logger->logAction('estoque update(ID: ' . $id_centro_custo . ')', (array) $data);
            return true;
        }

        return false;
    }

    function atualizarEstoque($id_estoque, $id_centro_custo, $dados)
    {
        $this->db->where('id', $id_estoque);
        if ($this->db->update('estoque', $dados)) {
            $this->logger->logAction('estoques update(ID: ' . $id_centro_custo . ')', (array) $dados);
            return true;
        }

        return false;
    }

    function getTotalEstoqueCentroCusto($id_centro_custo)
    {
        // $this->db->select('count (id)');
        // $this->db->from("produtos");
        // $this->db->where('id_centro_custo', $id_centro_custo);
        $this->db->select('SUM (em.quantidade)');
        $this->db->from("estoque_mov em");
        $this->db->join('produtos p', 'em.id_produto = p.id', 'LEFT');
        $this->db->where('p.id_centro_custo = ', $id_centro_custo);

        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            // var_dump($query->result());
            // die;
            return $query->result();
        }
        return false;
    }


    function getTotalEntradaMes($id_centro_custo)
    {
        $month = date('m');
        $data_start = "'2020-$month-01'";
        //$data_end = "'2020-$month-31'";
        $data_end = date("'Y-m-t'");

        $this->db->select('sum (em.quantidade)');
        $this->db->from("estoque_mov em");
        $this->db->join('produtos p', 'em.id_produto = p.id', 'LEFT');
        $this->db->where('flag_mov = 1 and  p.id_centro_custo = ' . $id_centro_custo . ' AND data_cadastro >= ' . $data_start . ' and  data_cadastro <= ' . $data_end . '');

        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            // var_dump($query->result());
            // die;
            return $query->result();
        }
        return false;
    }
    

    function getTotalSaidaMes($id_centro_custo)
    {


        $month = date('m');
        $data_start = "'2020-$month-01'";
        $data_end = date("'Y-m-t'");


        $this->db->select('sum (em.quantidade)');
        $this->db->from("estoque_mov em");
        $this->db->join('produtos p', 'em.id_produto = p.id', 'LEFT');
        $this->db->where('flag_mov = 0 and  p.id_centro_custo = ' . $id_centro_custo . ' AND data_cadastro >= ' . $data_start . ' and  data_cadastro <= ' .   $data_end . '');

        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            // var_dump($query->result());
            // die;
            return $query->result();
        }
        return false;
    }


    function validarExclusao($id_estoque, $id_centro_custo)
    {
        // $this->db->select('com.nome');
        // $this->db->from('servico com');

        // $where = "com.id NOT IN(select id_servico from veiculos where excluido = 0 and id_empresa = ".$id_empresa.")";
        // $this->db->where($where);
        // $this->db->where('com.excluido', 0);
        // $this->db->where('com.id', $id_servico);
        // $this->db->where('com.id_empresa', $id_empresa);
        // $this->db->order_by("nome", "ASC");

        // $query = $this->db->get();

        // if($query->num_rows() >= 1) {
        //     return $query->result();
        // }
        // return false;
        return true;
    }

    // function deletarEstoque($id_estoque){
    //     $data = array(
    //         'ativo' => 0
    //     );

    //     $this->db->where('id', $id_estoque);
    //     if($this->db->update('estoque', $data)){
    //         $this->logger->logAction('tipo_produtos delete(ID: '.$id_estoque.')', (array) $data);
    //         return true;
    //     }

    //     return false;
    // }

}
