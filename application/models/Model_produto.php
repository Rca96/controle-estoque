<?php
class Model_Produto extends CI_Model
{

    function getProdutos($id_centro_custo)
    {

        $where = array(
            'p.excluido'   => 0,
            'p.id_centro_custo' => $id_centro_custo
        );
        $this->db->select('
            STRING_AGG(tp.nome::text, \' | \') as nome_tipo,
            STRING_AGG(tp.id::text, \'|\') as id_tipo_produto,
            p.*, e.quantidade as qtd_estoque,  p.nome as nome_produto, p.cod_produto, um.abreviacao as unidade, um.id as id_unidade, p.qtd_maxima, p.qtd_minima');
        $this->db->from('produtos p');
        $this->db->join('estoque e', 'e.id_produto = p.id', 'LEFT');
        $this->db->join('unidade_medida um', 'um.id = p.id_unidade', 'LEFT');
        // $this->db->join('produto_tipo pt', 'p.id = pt.id_produto', 'LEFT');
        // $this->db->join('tipo_produto tp', '(tp.id::text)  IN (SELECT STRING_AGG(id_tipo_produto::text, \',\') FROM "produto_tipo" "pt" WHERE "id_produto" = "p"."id")', 'LEFT');
        $this->db->join('produto_tipo pt', 'pt.id_produto = p.id ', 'LEFT');
        $this->db->join('tipo_produto tp', 'tp.id = pt.id_tipo_produto AND pt.id_produto = p.id', 'LEFT');
        // $this->db->join('galeria_fotos gf', 'p.id = gf.id_produto AND "gf"."excluido" = 0 ', 'LEFT');
        $this->db->where($where);
        $this->db->order_by("p.nome", "ASC");
        $this->db->group_by("p.id, e.id, um.id");
        // $this->db->group_by("p.id, e.id, um.id, gf.id_produto");

        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        }
        return false;
    }

    function getProduto($id_produto = null, $id_centro_custo, $cod_produto = null)
    {
        $this->db->select('
        STRING_AGG(tp.nome::text, \' | \') as nome_tipo,
        STRING_AGG(tp.id::text, \'|\') as id_tipo_produto,
        p.*, p.nome as nome_produto, p.cod_produto, um.abreviacao as unidade,
         um.id as id_unidade, p.qtd_minima, e.quantidade as qtd_estoque');
        $this->db->from('produtos p');
        $this->db->join('estoque e', 'e.id_produto = p.id', 'LEFT');
        $this->db->join('unidade_medida um', 'um.id = p.id_unidade', 'LEFT');
        $this->db->join('produto_tipo pt', 'p.id = pt.id_produto', 'LEFT');
        $this->db->join('tipo_produto tp', 'pt.id_tipo_produto = tp.id', 'LEFT');
        $this->db->join('galeria_fotos gf', 'p.id = gf.id_produto AND "gf"."excluido" = 0 ', 'LEFT');
        $this->db->group_by("p.id, e.id, um.id");


        if ($id_produto != null)
            $this->db->where('p.id', $id_produto);

        if ($cod_produto != null)
            $this->db->where('p.cod_produto', $cod_produto);

        $this->db->where('p.id_centro_custo', $id_centro_custo);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        }
        return false;
    }
    // raul
    function alterarProduto($id_produto, $id_centro_custo, $data, $cod_produto = null)
    {
        $where = array(
            'id'   => $id_produto,
            'id_centro_custo' => $id_centro_custo
        );


        $this->db->where($where);


        if ($this->db->update('produtos', $data)) {
            //$this->logger->logAction('produtos update(ID: ' . $id_centro_custo . ')', (array) $data);
            return true;
        }

        return false;
    }

    function alterarTipoProduto($id_tp, $id_produto, $infoP)
    {
        // // var_dump($id_tp);
        // var_dump($infoP);
        // die;
        $where = array(
            'id' => $id_tp,
            'id_produto'   => $id_produto
        );
        $this->db->where($where);
        if ($this->db->delete('produto_tipo')) {
            $this->db->insert('produto_tipo', $infoP);
            //$last_id = $this->db->insert_id();

            return true;
        }

        // if ($this->db->insert('produto_tipo', $infoP)) {
        //     $last_id = $this->db->insert_id();

        //     return $last_id;
        // }

        return false;
    }

    function getByProduto($cod_produto, $id_centro_custo)
    {
        $this->db->select("*");
        $this->db->from("produtos");
        $this->db->where('cod_produto', $cod_produto);
        $this->db->where('id_centro_custo', $id_centro_custo);
        $this->db->where('ativo', 1);

        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        }
        return false;
    }

    function getTipoProdutos($id_produto)
    {
        $this->db->select("*");
        $this->db->from("produto_tipo");
        $this->db->where('id_produto', $id_produto);

        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        }
        return false;
    }

    function countFile($id_produto)
    {
        $where = array(
            'excluido' => 0,
            'id_produto'   => $id_produto
        );
        $this->db->select("*");
        $this->db->from("galeria_fotos");
        $this->db->where($where);

        $query = $this->db->get();
        if ($query->num_rows() >= 0) {
            return $query->result();
        }
        return false;
    }

    function getByName($nome, $id_centro_custo)
    {
        $this->db->select("*");
        $this->db->from("produtos");
        $this->db->where('nome', $nome);
        $this->db->where('id_centro_custo', $id_centro_custo);
        $this->db->where('ativo', 1);

        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        }
        return false;
    }
    function cadastrarProduto($data)
    {
        if ($this->db->insert('produtos', $data)) {
            $last_id = $this->db->insert_id();
            $this->logger->logAction('produtos create', (array) $data);
            return $last_id;
        }

        return false;
    }

    function cadastrarTipoProduto($data)
    {
        if ($this->db->insert('produto_tipo', $data)) {
            $last_id = $this->db->insert_id();
            // $this->logger->logAction('produtos create', (array) $data);
            return $last_id;
        }

        return false;
    }

    function cadastrarFoto($data)
    {

        if ($this->db->insert('galeria_fotos', $data)) {
            $last_id = $this->db->insert_id();
            // $this->logger->logAction('produtos create', (array) $data);
            return $last_id;
        }

        return false;
    }

    function getFotos($id_produto)
    {
        $where = array(
            'excluido' => 0,
            'id_produto'   => $id_produto
        );
        $this->db->select('id,id_produto, path');
        $this->db->from('galeria_fotos gf');
        //$this->db->join('produtos p', 'p.id_ = gf.id_produtos', 'LEFT');
        $this->db->where($where);


        $query = $this->db->get();

        if ($query->num_rows() >= 0) {
            return $query->result();
        }
        return false;
    }


    function getUnidadesMedidas()
    {
        $this->db->select('id, nome, abreviacao as unidade');
        $this->db->from('unidade_medida');
        $this->db->where('excluido', 0);
        $this->db->order_by("nome", "ASC");

        $query = $this->db->get();

        if ($query->num_rows() >= 1) {
            return $query->result();
        }
        return false;
    }

    public function deletarAnexo($id)
    {

        $this->db->where('id', $id);
        // $query = $this->db->get();
        if ($this->db->query("UPDATE galeria_fotos SET excluido = 1 WHERE id = $id")) {

            return true;
        }

        //  $this->logger->logAction('arquivos_municipio delete(ID: ' . $id . ')');
        //  $this->db->query("UPDATE galeria_fotos SET excluido = 1 WHERE id = $id");
        return false;
    }

    function validarExclusao($id_produto, $id_centro_custo)
    {
        $where = array(
            'em.id_produto' => $id_produto,
            'p.id_centro_custo = ' => $id_centro_custo,
            // 'SUM (em.quantidade) =' => 0
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

    public function deletarProduto($id)
    {

        $this->db->where('id', $id);
        // $query = $this->db->get();
        if ($this->db->query("UPDATE produtos SET excluido = 1 WHERE id = $id")) {

            return true;
        }

        //  $this->logger->logAction('arquivos_municipio delete(ID: ' . $id . ')');
        //  $this->db->query("UPDATE galeria_fotos SET excluido = 1 WHERE id = $id");
        return false;
    }
}
