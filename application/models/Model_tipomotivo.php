<?php
class Model_tipomotivo extends CI_Model
{

    function getTipoMotivos($id_centro_custo)
    {
        $this->db->select("*");
        $this->db->from('motivo');
        $this->db->where('id_centro_custo', $id_centro_custo);
        $this->db->where('excluido', 0);
        $this->db->order_by("nome", "ASC");

        $query = $this->db->get();

        if ($query->num_rows() >= 1) {
            return $query->result();
        }
        return false;
    }


    function getTipoMotivo($id_tipomotivo, $id_centro_custo)
    {
        $this->db->select("*");

        $this->db->from("motivo");

        $this->db->where('id', $id_tipomotivo);
        $this->db->where('id_centro_custo', $id_centro_custo);
        $this->db->where('excluido', 0);

        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        }
        return false;
    }

    function getByName($nome, $id_centro_custo)
    {
        $this->db->select("*");
        $this->db->from("motivo");
        $this->db->where('nome', $nome);
        $this->db->where('id_centro_custo', $id_centro_custo);
        $this->db->where('excluido', 0);

        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        }
        return false;
    }

    function cadastrarTipoMotivo($data)
    {
        if ($this->db->insert('motivo', $data)) {
            $last_id = $this->db->insert_id();
            $this->logger->logAction('motivo create', (array) $data);
            return $last_id;
        }

        return false;
    }

    function alterarTipoMotivo($id_tipomotivo, $id_centro_custo, $data)
    {
        $this->db->where('id', $id_tipomotivo);
        $this->db->where('id_centro_custo', $id_centro_custo);
        if ($this->db->update('motivo', $data)) {
            // $this->logger->logAction('tipo_produtos update(ID: ' . $id_centro_custo . ')', (array) $data);
            return true;
        }

        return false;
    }


    function validarExclusao($id_tipomotivo, $id_centro_custo)
    {
        $where = array(
            'em.id_motivo' => $id_tipomotivo,
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

    function deletarTipoMotivo($id_tipoproduto)
    {
        $data = array(
            'excluido' => 1
        );

        $this->db->where('id', $id_tipoproduto);
        if ($this->db->update('motivo', $data)) {
            // $this->logger->logAction('tipo_produtos delete(ID: '.$id_tipoproduto.')', (array) $data);
            return true;
        }

        return false;
    }
}
