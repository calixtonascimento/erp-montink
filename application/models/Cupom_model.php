<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cupom_model extends CI_Model {

    public function listar() {
        return $this->db->get('cupons')->result_array();
    }

    public function inserir($dados) {
        return $this->db->insert('cupons', $dados);
    }

    public function excluir($id) {
        return $this->db->where('id', $id)->delete('cupons');
    }

    public function buscar_por_codigo($codigo) {
        return $this->db
                    ->where('codigo', $codigo)
                    ->where('quantidade >', 0)
                    ->where('validade >=', date('Y-m-d'))
                    ->get('cupons')
                    ->row_array();
    }

    public function reduzir_quantidade($id) {
        $this->db->set('quantidade', 'quantidade - 1', false)
                 ->where('id', $id)
                 ->update('cupons');
    }
}
