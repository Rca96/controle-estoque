<?php
class Model_Relatorio extends CI_Model {


    function getRelatorio(){

        $this->db->select('*');
        $this->db->from('produto');
        $this->db->where('excluido', 0);    
        $this->db->where('ativo', 0);    
        
        $query = $this->db->get();
    
        if($query->num_rows() >= 1) {
            return $query->result();
        }
        return false;
    }

    function getRelatorioData(){

        $this->db->select('*');
        $this->db->from('produto');
        $this->db->where('excluido', 0);
        $this->db->where('ativo', 1);
        
        $query = $this->db->get();
    
        if($query->num_rows() >= 1) {
            return $query->result();
        }
        return false;
    }
    
}

?>