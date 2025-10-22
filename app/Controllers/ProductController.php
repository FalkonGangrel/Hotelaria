<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\ProductSupplier;
use App\Helpers\Auth;

class ProductController
{
    public function index()
    {
        Auth::check(['master', 'admin', 'fornecedor']);
        $products = Product::allWithSuppliers();
        view('products/list', [
            'title' => 'Lista de Produtos',
            'products' => $products
        ]);
    }

    public function create()
    {
        Auth::check(['master', 'admin']);
        $suppliers = Supplier::all();
        view('products/form', [
            'title' => 'Novo Produto',
            'suppliers' => $suppliers
        ]);
    }

    public function store()
    {
        Auth::check(['master', 'admin']);

        $data = [
            'nome' => trim($_POST['nome'] ?? ''),
            'descricao' => trim($_POST['descricao'] ?? ''),
            'preco_base' => floatval($_POST['preco_base'] ?? 0),
            'ativo' => isset($_POST['ativo']) ? 1 : 0
        ];

        if (empty($data['nome'])) {
            $_SESSION['error'] = 'O nome do produto é obrigatório.';
            return redirect('/produtos/novo');
        }

        $productId = Product::create($data);

        if (!empty($_POST['fornecedores'])) {
            ProductSupplier::syncSuppliers($productId, $_POST['fornecedores']);
        }

        $_SESSION['success'] = 'Produto cadastrado com sucesso!';
        return redirect('/produtos');
    }

    public function edit($id)
    {
        Auth::check(['master', 'admin']);
        $product = Product::find($id);
        $suppliers = Supplier::all();
        $selected = ProductSupplier::getSuppliersByProduct($id);

        if (!$product) {
            $_SESSION['error'] = 'Produto não encontrado.';
            return redirect('/produtos');
        }

        view('products/form', [
            'title' => 'Editar Produto',
            'product' => $product,
            'suppliers' => $suppliers,
            'selected' => $selected
        ]);
    }

    public function update($id)
    {
        Auth::check(['master', 'admin']);

        $data = [
            'nome' => trim($_POST['nome'] ?? ''),
            'descricao' => trim($_POST['descricao'] ?? ''),
            'preco_base' => floatval($_POST['preco_base'] ?? 0),
            'ativo' => isset($_POST['ativo']) ? 1 : 0
        ];

        if (empty($data['nome'])) {
            $_SESSION['error'] = 'O nome do produto é obrigatório.';
            return redirect('/produtos/editar/' . $id);
        }

        Product::update($id, $data);

        if (isset($_POST['fornecedores'])) {
            ProductSupplier::syncSuppliers($id, $_POST['fornecedores']);
        }

        $_SESSION['success'] = 'Produto atualizado com sucesso!';
        return redirect('/produtos');
    }

    public function delete($id)
    {
        Auth::check(['master']);
        Product::delete($id);
        $_SESSION['success'] = 'Produto removido com sucesso!';
        return redirect('/produtos');
    }
}
