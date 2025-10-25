<?php

namespace App\Controllers;

use App\Models\Product;
use App\Helpers\Auth;

class ProductController
{
    public function index(): void
    {
        Auth::authorize(['master','admin','fornecedor']);

        $product = new Product();
        $produtos = $product->all();

        view('produtos/list', [
            'title' => 'Lista de Produtos',
            'products' => $produtos
        ]);
    }

    public function create(): void
    {
        Auth::authorize(['master', 'admin']);
        view('produtos/form', ['title' => 'Novo Produto']);
    }

    public function store(): void
    {
        Auth::authorize(['master', 'admin']);

        $data = [
            'sku' => trim($_POST['sku'] ?? ''),
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'price' => floatval($_POST['price'] ?? 0),
            'active' => isset($_POST['active']) ? true : false
        ];

        if (empty($data['title'])) {
            $_SESSION['error'] = 'O nome do produto é obrigatório.';
            redirect($_ENV['APP_BASE'].'/produtos/novo');
            return;
        }

        $product = new Product();
        $id = $product->create($data);

        if ($id) {
            $_SESSION['success'] = 'Produto cadastrado com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao cadastrar o produto.';
        }

        redirect($_ENV['APP_BASE'].'/produtos');
    }

    public function edit(int $id): void
    {
        Auth::authorize(['master', 'admin']);

        $product = new Product();
        $item = $product->find($id);

        if (!$item) {
            $_SESSION['error'] = 'Produto não encontrado.';
            redirect($_ENV['APP_BASE'].'/produtos');
            return;
        }

        view('produtos/form', [
            'title' => 'Editar Produto',
            'product' => $item
        ]);
    }

    public function update(int $id): void
    {
        Auth::authorize(['master', 'admin']);

        $data = [
            'sku' => trim($_POST['sku'] ?? ''),
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'price' => floatval($_POST['price'] ?? 0),
            'active' => isset($_POST['active']) ? true : false
        ];

        $product = new Product();
        $ok = $product->update($id, $data);

        $_SESSION[$ok ? 'success' : 'error'] = $ok
            ? 'Produto atualizado com sucesso!'
            : 'Erro ao atualizar produto.';

        redirect($_ENV['APP_BASE'].'/produtos');
    }

    public function delete(int $id): void
    {
        Auth::authorize(['master', 'admin']);

        $product = new Product();
        $product->delete($id);

        $_SESSION['success'] = 'Produto removido com sucesso!';
        redirect($_ENV['APP_BASE'].'/produtos');
    }

    public function reactivate(int $id): void
    {
        Auth::authorize(['master', 'admin']);

        $product = new Product();
        $product->reactivate($id);

        $_SESSION['success'] = 'Produto reativado com sucesso!';
        redirect($_ENV['APP_BASE'].'/produtos');
    }
}
