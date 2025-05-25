<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teste extends CI_Controller {

    public function index(){
        $this->load->database();
        var_dump($this->db);
    }
}
