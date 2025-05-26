<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Estoque_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_by_produto($produto_id)
    {
        return $this->db->get_where('estoque', ['produto_id' => $produto_id])->result();
    }

    public function save($data)
    {
        $this->db->insert('estoque', $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id)->update('estoque', $data);
    }
}
