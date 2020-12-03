<?php
class Model_Login extends CI_Model {

    function login($login) {        
            
        $this->db->select('usu.id, 
            usu.nome, 
            usu.matricula,
            usu.nivel,
            usu.id_local,
            cc.id as id_centro_custo, 
            cc.nome as nome_custo,
            cc.tipo_estoque');

        $this->db->from('usuarios usu');
        $this->db->join('centro_custo cc', 'usu.id_centro_custo = cc.id');
        $this->db->where('usu.login', $login);
        $this->db->where('usu.ativo', 1);
        $this->db->where('cc.excluido', 0);
        $this->db->where('cc.ativo', 1);

        $this->db->limit(1);
        
        $query = $this->db->get();
        
        if($query->num_rows() == 1) {
            return $query->result();
        }
        return false;
    }

    public function getUser($id)
    {
        $q = $this->db->get_where('usuarios', array('id' => $id), 1);  
        if($this->db->affected_rows() > 0){
            $row = $q->row();
            return $row;
        }else{
            error_log('Usuário não encontrado('.$id.')');
            return false;
        }
    }

    function update_access($id) {
		$headers = (function_exists('apache_request_headers'))?apache_request_headers():$_SERVER;
		if (array_key_exists('X-Forwarded-For', $headers) && filter_var($headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) $user_ip = $headers['X-Forwarded-For'];
        elseif (array_key_exists( 'HTTP_X_FORWARDED_FOR', $headers) && filter_var($headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) $user_ip = $headers['HTTP_X_FORWARDED_FOR'];
        else $user_ip = filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
        $info = array('user_id' => $id,
                      'date' => date('Y-m-d'),
                      'hora' => date('H:i:s'),
                      'ip' => $user_ip);

        return ($this->db->insert($this->db->dbprefix('access'), $info))?true:false;
    }

    public function insertToken($user_id)
    {   
        $token = substr(sha1(rand()), 0, 30); 
        $date = date('Y-m-d');
        
        $string = array(
                'token'=> $token,
                'id_user'=> $user_id,
                'usuario_app'=> 0,
                'date_created'=> $date
            );
        $query = $this->db->insert_string('tokens',$string);
        $this->db->query($query);
        return $token . $user_id;
        
    }
    
    public function isTokenValid($token)
    {
        $tkn = substr($token,0,30);
        $uid = substr($token,30);      
       
        $q = $this->db->get_where('tokens', array(
            'tokens.token'       => $tkn, 
            'tokens.id_user'     => $uid,
            'tokens.usuario_app' => 0), 1);                         
        
        if($this->db->affected_rows() > 0){
            $row = $q->row();             
            
            $created = $row->date_created;
            $createdTS = strtotime($created);
            $today = date('Y-m-d'); 
            $todayTS = strtotime($today);
            
            if($createdTS != $todayTS){
                return false;
            }
            
            $user_info = $this->getUser($row->id_user);
            return $user_info;
            
        }else{
            return false;
        }
        
    }

 
}
?>