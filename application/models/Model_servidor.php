<?php
class Model_Servidor extends CI_Model {

    function getServidor(){
        
        $CI = &get_instance();
        $this->db2 = $CI->load->database('dbservidor', TRUE);

        $this->db2->select('R.*, R.nome as nome_servidor');
        $this->db2->from('cadfunrh R');
        $this->db2->where('R.data_demissao', NULL);
        $this->db2->order_by("R.nome", "ASC");
        $query = $this->db2->get();
    
        if($query->num_rows() >= 1) {
            return $query->result();
        }
        return false;
    }

    function getServidores($matricula){

        $CI = &get_instance();
        $this->db2 = $CI->load->database('dbservidor', TRUE);

        $this->db2->select('*');
        $this->db2->from('cadfunrh');
        

        $this->db2->where('matricula', $matricula);
        $query = $this->db2->get();
    
        if($query->num_rows() >= 1) {
            return $query->result();
        }
        return false;
    }

     function getServidoresToJson(){
        
        $CI = &get_instance();
        $this->db2 = $CI->load->database('dbservidor', TRUE);

        $this->db2->select('R.matricula, CONCAT(CONCAT("R"."nome", \' | CPF : \'), "R"."cpf") as nome');
        $this->db2->from('cadfunrh R');
        $this->db2->where('R.data_demissao =', NULL);
        $this->db2->where('R.nome !=', null);
        
        $query = $this->db2->get();
    
        if($query->num_rows() >= 1) {

            return $query->result();
            
        }
        return false;
    }

    function getLocal($id_local){
        
        $CI = &get_instance();
        
        $this->db2 = $CI->load->database('dbservidor', TRUE);

        $this->db2->select('*, abrev as nome_local');
        $this->db2->from('local');
        $this->db2->where('id', $id_local);
        
        $this->db2->where('ativo', 0);
        $query = $this->db2->get();
    
        if($query->num_rows() >= 1) {
            return $query->result();
        }
        return false;
    }
}

?>