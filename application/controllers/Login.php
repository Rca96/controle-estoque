<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends ES_Controller
{

    function __construct()
    {

        parent::__construct();

        $this->load->model('model_login', '', TRUE);
    }

    function index()
    {
        // $url = parse_url($_SERVER['REQUEST_URI']);
        // if(!isset($url['query']))
        // {
        //     $this->logout();
        // }

        // parse_str($url['query'], $params);
        // $jwt = $params['token'];

        // $key = "c7b34762-1284-4ea3-90a2-01f78e9761bf";

        // try {
        //     $decoded = \Firebase\JWT\JWT::decode($jwt, $key, array('HS256'));
        //     $login = $decoded->login;
        // } 
        // catch (\Firebase\JWT\SignatureInvalidException $e) {
        //     print_r('Signature error');
        //     header("location: http://pma.sp.gov.br/intranet/?error=403");
        //     die;
        // }
        // catch (\Firebase\JWT\BeforeValidException $e) {
        //     print_r("Before valid Exception");
        //     header("location: http://pma.sp.gov.br/intranet/?error=403");
        //     die;
        // }
        // catch (\Firebase\JWT\ExpiredException $e) {
        //     print_r("Expired Token");
        //     header("location: http://pma.sp.gov.br/intranet/?error=403");
        //     die;
        // }
        $login = "izabela.orzari";
        $result = $this->model_login->login($login);
        if ($result) {

            $sess_array = array();
            $usuario = $result[0];

            $sess_array = array(
                'id' => $usuario->id,
                'nome' => $usuario->nome,
                'nivel' => $usuario->nivel,
                'nome_custo' => $usuario->nome_custo,
                'matricula' => $usuario->matricula,
                'id_centro_custo' => $usuario->id_centro_custo,
                'tipo_estoque' => $usuario->tipo_estoque
            );

            $this->session->set_userdata('logged_in', $sess_array);

            redirect('estoque', 'refresh');
        } else {
            print_r("Expired Token");
            header("location: http://pma.sp.gov.br/intranet/?error=403");
            die;
        }
    }


    function logout()
    {
        $this->session->unset_userdata('logged_in');
        redirect('https://www.pma.sp.gov.br/intranet/');
    }
}
