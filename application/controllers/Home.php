<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends ES_Controller {

    private $id_user;
    private $id_centro_custo;
    private $user_matricula;

    function __construct() {
        parent::__construct();
        $this->logado();
        $this->load->model('model_home');
     

        $this->id_user = $this->session->userdata['logged_in']['id'];
        $this->id_nivel = $this->session->userdata['logged_in']['nivel'];
        $this->user_matricula = $this->session->userdata['logged_in']['matricula'];
        
        $this->id_centro_custo = $this->session->userdata['logged_in']['id_centro_custo'];
        $this->data['tipo_estoque'] = $this->session->userdata['logged_in']['tipo_estoque'];
        $this->data = array();
    }


    function index()
    {
        

        if($this->id_nivel == 1 || $this->id_nivel == 2  || $this->id_nivel == 2)
        {
            $this->load->view('home', $this->data);
        }
    }

}
