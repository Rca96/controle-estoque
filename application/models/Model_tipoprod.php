<?php
class Model_tipoprod extends CI_Model
{

    function getTipoProdutos($id_centro_custo)
    {
        $this->db->select("*");
        $this->db->from('tipo_produto');
        $this->db->where('id_centro_custo', $id_centro_custo);
        $this->db->where('ativo', 1);
        $this->db->order_by("nome", "ASC");

        $query = $this->db->get();

        if ($query->num_rows() >= 1) {
            return $query->result();
        }
        return false;
    }


    function getTipoProduto($id_tipoproduto, $id_centro_custo)
    {
        $this->db->select("*");

        $this->db->from("tipo_produto");

        $this->db->where('id', $id_tipoproduto);
        $this->db->where('id_centro_custo', $id_centro_custo);
        $this->db->where('ativo', 1);

        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        }
        return false;
    }

    function getByName($nome, $id_centro_custo)
    {
        $this->db->select("*");
        $this->db->from("tipo_produto");
        $this->db->where('nome', $nome);
        $this->db->where('id_centro_custo', $id_centro_custo);
        $this->db->where('ativo', 1);

        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        }
        return false;
    }

    function cadastrarTipoProduto($data)
    {
        if ($this->db->insert('tipo_produto', $data)) {
            $last_id = $this->db->insert_id();
            $this->logger->logAction('tipo_produtos create', (array) $data);
            return $last_id;
        }

        return false;
    }

    function alterarTipoProduto($id_tipoproduto, $id_centro_custo, $data)
    {
        $this->db->where('id', $id_tipoproduto);
        $this->db->where('id_centro_custo', $id_centro_custo);
        if ($this->db->update('tipo_produto', $data)) {
            $this->logger->logAction('tipo_produtos update(ID: ' . $id_centro_custo . ')', (array) $data);
            return true;
        }

        return false;
    }


    function validarExclusao($id_tipoproduto, $id_centro_custo)
    {
        $where = array(
            'p.id' => $id_tipoproduto,
            'p.id_centro_custo = ' => $id_centro_custo
        );
        $this->db->select('*');
        $this->db->from("estoque_mov em");
        $this->db->join('produtos p', 'em.id_produto = p.id', 'LEFT');
        $this->db->where($where);

        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            // var_dump($query->result());
            // die;
            return $query->result();
        }
        return false;
    }

    function deletarTipoProduto($id_tipoproduto)
    {
        $data = array(
            'ativo' => 0
        );

        $this->db->where('id', $id_tipoproduto);
        if ($this->db->update('tipo_produto', $data)) {
            //$this->logger->logAction('tipo_produtos delete(ID: '.$id_tipoproduto.')', (array) $data);
            return true;
        }

        return false;
    }
}
