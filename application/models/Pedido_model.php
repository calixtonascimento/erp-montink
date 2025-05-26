<?php
class Pedido_model extends CI_Model
{
    public function inserir_pedido($dados_pedido, $itens)
    {
        $this->db->trans_start();

        $this->db->insert('pedidos', $dados_pedido);
        $pedido_id = $this->db->insert_id();

        foreach ($itens as $item) {
            $item['pedido_id'] = $pedido_id;
            $this->db->insert('pedido_itens', $item);

            // Já aproveitei para atualizar o estoque
            $this->db->set('quantidade', "quantidade - {$item['quantidade']}", false);
            $this->db->where('produto_id', $item['produto_id']);
            $this->db->update('estoque');
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return $pedido_id;
        }
    }
}
