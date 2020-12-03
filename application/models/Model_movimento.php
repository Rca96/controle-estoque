<?php
class Model_Movimento extends CI_Model
{

    function getMovimentos($id_centro_custo, $flag_mov = NULL)
    {

        $this->db->select('M.*, M.flag_mov as flag_ES, p.nome as nome_produto, p.cod_produto, 
        e.quantidade as qtd_estoque, um.abreviacao as unidade, tp.nome as nome_tipo, m.nome as nome_motivo, M.nro_documento as doc, M.id');
        $this->db->from('estoque_mov M');
        $this->db->join('produtos p', 'p.id = M.id_produto', 'LEFT');
        $this->db->join('estoque e', 'e.id_produto = M.id_produto', 'LEFT');
        $this->db->join('unidade_medida um', 'um.id = p.id_unidade', 'LEFT');
        $this->db->join('tipo_produto tp', 'p.id_tipoproduto = tp.id', 'LEFT');
        $this->db->join('motivo m', 'M.id_motivo = m.id', 'LEFT');
        $this->db->where('p.id_centro_custo', $id_centro_custo);
        $this->db->where('M.flag_mov', $flag_mov);
        $this->db->order_by("M.data_movimento, p.nome", "ASC");

        $query = $this->db->get('');
        if ($query->num_rows() >= 1) {
            return $query->result();
        }
        return false;
    }

    function getMovimento($id_movimento, $id_centro_custo)
    {

        $this->db->select('M.*, M.flag_mov as flag_ES, p.nome as nome_produto, p.cod_produto, e.quantidade as qtd_estoque,
         um.abreviacao as unidade, tp.nome as nome_tipo');
        $this->db->from('estoque_mov M');
        $this->db->join('produtos p', 'p.id = M.id_produto');
        $this->db->join('estoque e', 'e.id_produto = M.id_produto', 'LEFT');
        $this->db->join('unidade_medida um', 'um.id = p.id_unidade', 'LEFT');
        $this->db->join('tipo_produto tp', 'p.id_tipoproduto = tp.id', 'LEFT');
        $this->db->where('p.id_centro_custo', $id_centro_custo);
        $this->db->where('M.id', $id_movimento);

        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        }
        return false;
    }

    function cadastrarMovimento($data)
    {
        if ($this->db->insert('estoque_mov', $data)) {
            $last_id = $this->db->insert_id();
            $this->logger->logAction('estoque_mov create', (array) $data);
            return $last_id;
        }

        return false;
    }

    function cadastrarFicha($ficha)
    {
        // var_dump($ficha);
        // die;
        if ($this->db->insert('estoque_mov_detalhe', $ficha)) {
            $last_id = $this->db->insert_id();
            // $this->logger->logAction('estoque_mov create', (array) $data);
            return $last_id;
        }

        return false;
    }

    function getFicha()
    {
        $row = $this->db->select("id")->limit(1)->order_by('id', "DESC")->get("estoque_mov_detalhe")->row();
        // $this->db->select('id_ficha');
        // $this->db->from('estoque_mov_detalhe');

        // $query = $this->db->get();
        // if ($query->num_rows() >= 0) {
        //     return $query->result();
        // }
        // return false;
        return $row;
        // var_dump($row);
        // die;
    }

    function getFichaConteudo($id_mov)
    {
        // var_dump($id_mov);
        // die;
        $this->db->select('*');
        $this->db->from('estoque_mov_detalhe');
        $this->db->where('id_estoque_mov', $id_mov);
        $query = $this->db->get();
        if ($query->num_rows() >= 0) {
            return $query->result();
        }
        return false;
    }

    function getFichaConteudoSaida($id_mov)
    {
        $array = array('retirado' => 1, 'id_estoque_mov_saida' => $id_mov);
        $this->db->select('emd.*, em.nro_documento');
        $this->db->from('estoque_mov_detalhe emd');
        $this->db->join('estoque_mov em', 'emd.id_estoque_mov = em.id', 'LEFT');
        $this->db->where($array);
        $query = $this->db->get();
        if ($query->num_rows() >= 0) {
            return $query->result();
        }
        return false;
    }

    function updateFicha($id_ficha, $data)
    {
        // var_dump($id_ficha);

        // var_dump($data);
        // die;
        $this->db->where('id', $id_ficha);
        if ($this->db->update('estoque_mov_detalhe', $data)) {
            return true;
        }

        return false;
    }

    function validaProdutoFicha($id_ficha, $id_produto)
    {
        // var_dump($id_ficha);
        // var_dump($id_produto);
        // die;
        $array = array('em.id_produto' => $id_produto, 'emd.id' => $id_ficha);
        $this->db->select('emd.*');
        $this->db->from('estoque_mov_detalhe emd');
        $this->db->join('estoque_mov em', 'em.id=emd.id_estoque_mov');
        $this->db->where($array);
        $query = $this->db->get();
        if ($query->num_rows() >= 0) {
            return $query->result();
        }
        return false;
    }
}
