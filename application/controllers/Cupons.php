<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cupons extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Cupom_model');
    }

    public function index() {
        $data['cupons'] = $this->Cupom_model->listar();
        $this->load->view('cupons/index', $data);
    }

    public function salvar() {
        $dados = [
            'codigo' => $this->input->post('codigo'),
            'tipo' => $this->input->post('tipo'),
            'valor' => $this->input->post('valor'),
            'quantidade' => $this->input->post('quantidade'),
            'validade' => $this->input->post('validade')
        ];

        $this->Cupom_model->inserir($dados);
        redirect('cupons');
    }

    public function excluir($id) {
        $this->Cupom_model->excluir($id);
        redirect('cupons');
    }
}
