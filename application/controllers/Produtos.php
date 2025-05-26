<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produtos extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Produto_model');
        $this->load->model('Estoque_model');
        $this->load->helper(['url', 'form']);
        $this->load->library('session');
    }

    public function index()
    {
        $data['produtos'] = $this->Produto_model->get_all();
        $this->load->view('produtos/index', $data);
    }

    public function salvar()
    {
        $produto = [
            'nome'  => $this->input->post('nome'),
            'preco' => $this->input->post('preco')
        ];

        $produto_id = $this->input->post('produto_id');

        if ($produto_id) {
            $this->Produto_model->update($produto_id, $produto);
        } else {
            $produto_id = $this->Produto_model->save($produto);
        }

        $estoque = [
            'produto_id' => $produto_id,
            'variacao'   => $this->input->post('variacao'),
            'quantidade' => $this->input->post('quantidade')
        ];

        if ($this->input->post('estoque_id')) {
            $this->Estoque_model->update($this->input->post('estoque_id'), $estoque);
        } else {
            $this->Estoque_model->save($estoque);
        }

        redirect('produtos');
    }

    public function editar($id)
    {
        $data['produto'] = $this->Produto_model->get_by_id($id);
        $data['estoque'] = $this->Estoque_model->get_by_produto($id);
        $this->load->view('produtos/form', $data);
    }

    public function novo()
    {
        $this->load->view('produtos/form');
    }

    public function adicionar_ao_carrinho($produto_id)
    {
        $produto = $this->Produto_model->get_by_id($produto_id);
        if (!$produto) {
            show_404();
        }

        $carrinho = $this->session->userdata('carrinho');
        if (!$carrinho) {
            $carrinho = [];
        }

        // Verifica se já existe no carrinho para somar quantidade
        if (isset($carrinho[$produto_id])) {
            $carrinho[$produto_id]['quantidade']++;
        } else {
            $carrinho[$produto_id] = [
                'produto_id' => $produto->id,
                'nome' => $produto->nome,
                'preco' => $produto->preco,
                'quantidade' => 1,
            ];
        }

        $this->session->set_userdata('carrinho', $carrinho);

        redirect('produtos/carrinho');
    }

    public function carrinho()
    {
        $data['carrinho'] = $this->session->userdata('carrinho');
        if (!$data['carrinho']) {
            $data['carrinho'] = [];
        }

        // Calcular subtotal
        $subtotal = 0;
        foreach ($data['carrinho'] as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }
        $data['subtotal'] = $subtotal;

        // Calcular frete conforme regra
        if ($subtotal >= 52 && $subtotal <= 166.59) {
            $frete = 15;
        } elseif ($subtotal > 200) {
            $frete = 0;
        } else {
            $frete = 20;
        }
        $data['desconto'] = 0;
        $data['frete'] = $frete;
        $data['total'] = $subtotal - $data['desconto'] + $frete;

        $this->load->view('produtos/carrinho', $data);
    }

    public function alterar_quantidade($produto_id, $acao)
    {
        $carrinho = $this->session->userdata('carrinho');
        if (!$carrinho || !isset($carrinho[$produto_id])) {
            redirect('produtos/carrinho');
        }

        if ($acao == 1) {
            $carrinho[$produto_id]['quantidade']++;
        } elseif ($acao == -1) {
            $carrinho[$produto_id]['quantidade']--;
            if ($carrinho[$produto_id]['quantidade'] <= 0) {
                unset($carrinho[$produto_id]);
            }
        }

        $this->session->set_userdata('carrinho', $carrinho);
        redirect('produtos/carrinho');
    }

    public function remover_do_carrinho($produto_id)
    {
        $carrinho = $this->session->userdata('carrinho');
        if ($carrinho && isset($carrinho[$produto_id])) {
            unset($carrinho[$produto_id]);
            $this->session->set_userdata('carrinho', $carrinho);
        }
        redirect('produtos/carrinho');
    }

    public function finalizar_pedido()
    {
        $carrinho = $this->session->userdata('carrinho');
        $cep = $this->input->post('cep');
        $cupom_codigo = $this->input->post('cupom');

        if (!$carrinho || count($carrinho) == 0) {
            redirect('produtos/carrinho');
        }

        $subtotal = 0;
        $itens = [];
        foreach ($carrinho as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
            $itens[] = [
                'produto_id'    => $item['produto_id'],
                'quantidade'    => $item['quantidade'],
                'preco_unitario' => $item['preco'],
            ];
        }

        // 🚚 Regras de frete
        if ($subtotal >= 52 && $subtotal <= 166.59) {
            $frete = 15.00;
        } elseif ($subtotal > 200) {
            $frete = 0.00;
        } else {
            $frete = 20.00;
        }

        // 🎟️ Aplicação de cupom (se houver)
        $desconto = 0;
        $cupom_aplicado = null;

        if ($cupom_codigo) {
            $this->load->model('Cupom_model');
            $cupom = $this->Cupom_model->buscar_por_codigo($cupom_codigo);

            if ($cupom) {
                if ($cupom['tipo'] == 'percentual') {
                    $desconto = ($subtotal * ($cupom['valor'] / 100));
                } elseif ($cupom['tipo'] == 'fixo') {
                    $desconto = $cupom['valor'];
                }

                if ($desconto > $subtotal) {
                    $desconto = $subtotal;
                }

                $cupom_aplicado = $cupom['codigo'];

                $this->Cupom_model->reduzir_quantidade($cupom['id']);
            }
        }

        $total = ($subtotal - $desconto) + $frete;

        $dados_pedido = [
            'subtotal' => $subtotal,
            'frete'    => $frete,
            'desconto' => $desconto,
            'total'    => $total,
            'cep'      => $cep,
            'cupom'    => $cupom_aplicado,
            'status'   => 'pendente'
        ];

        $this->load->model('Pedido_model');
        $pedido_id = $this->Pedido_model->inserir_pedido($dados_pedido, $itens);

        if ($pedido_id) {
            $this->session->unset_userdata('carrinho');
            $this->load->view('produtos/finalizado', ['pedido_id' => $pedido_id]);
        } else {
            echo "Erro ao salvar o pedido. Tente novamente.";
        }
    }

    public function validar_cupom()
    {
        $codigo = $this->input->post('cupom');
        $this->load->model('Cupom_model');

        $response = ['valido' => false, 'mensagem' => 'Cupom inválido', 'desconto' => 0];

        if ($codigo) {
            $cupom = $this->Cupom_model->buscar_por_codigo($codigo);

            if ($cupom) {
                // Verificar validade e quantidade
                $hoje = date('Y-m-d');
                if (($cupom['validade'] === null || $cupom['validade'] >= $hoje) && $cupom['quantidade'] > 0) {
                    $carrinho = $this->session->userdata('carrinho');
                    $subtotal = 0;
                    foreach ($carrinho as $item) {
                        $subtotal += $item['preco'] * $item['quantidade'];
                    }

                    if ($cupom['tipo'] == 'percentual') {
                        $desconto = ($subtotal * ($cupom['valor'] / 100));
                    } else {
                        $desconto = $cupom['valor'];
                    }

                    if ($desconto > $subtotal) {
                        $desconto = $subtotal;
                    }

                    $response = [
                        'valido' => true,
                        'mensagem' => 'Cupom aplicado com sucesso!',
                        'desconto' => $desconto,
                    ];
                } else {
                    $response['mensagem'] = 'Cupom expirado ou esgotado.';
                }
            }
        }

        echo json_encode($response);
    }
}
