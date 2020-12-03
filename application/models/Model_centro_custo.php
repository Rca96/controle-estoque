<?php
class Model_centro_custo extends CI_Model {

    function getCentro_custo(){

        $this->db->select('*');
        $this->db->from('centro_custo');
        $this->db->order_by("nome", "ASC");
        
        $query = $this->db->get();
    
        if($query->num_rows() >= 1) {
            return $query->result();
        }
        return false;
    }
}

?>