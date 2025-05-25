<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teste extends CI_Controller {

    public function index(){
        $this->load->database();
        if($this->db->conn_id){
            echo "Conexão com o banco de dados bem-sucedida!";
        } else {
            echo "Falha na conexão com o banco de dados!";
        }
    }
}
