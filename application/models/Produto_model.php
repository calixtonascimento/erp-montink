<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produto_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        return $this->db->get('produtos')->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('produtos', ['id' => $id])->row();
    }

    public function save($data)
    {
        $this->db->insert('produtos', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id)->update('produtos', $data);
    }
}
