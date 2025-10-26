<?php
namespace App\Controllers;

use App\Models\Supplier;
use App\Models\User;
use App\Helpers\Auth;

class SupplierController
{
    public function index()
    {
        Auth::authorize(['master','admin','fornecedor']);

        $model = new Supplier();
        $suppliers = $model->all();


        view('fornecedores/list', [
            'title' => 'Lista de Fornecedores',
            'suppliers' => $suppliers
        ]);
    }

    public function create(): void
    {
        Auth::authorize(['master', 'admin']);
        $user = new User();
        $users = $user->all();
        $params = [
            'title' => 'Novo Fornecedor',
            'users' => $users
        ];
        view('fornecedores/form', $params);
    }

    public function store(): void
    {
        Auth::authorize(['master', 'admin']);

        $data = [
            'user_id' => $_POST['user_id'] ?? null,
            'company_name' => trim($_POST['company_name']),
            'user_id' => $_SESSION['user']['id']
        ];

        if (empty($data['company_name'])) {
            $_SESSION['error'] = 'O nome da empresa é obrigatório.';
            redirect($_ENV['APP_BASE'].'/fornecedores/novo');
            return;
        }

        $model = new Supplier();
        $id = $model->create($data);

        if ($id) {
            $_SESSION['success'] = 'Fornecedor cadastrado com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao cadastrar o fornecedor.';
        }

        redirect($_ENV['APP_BASE'].'/fornecedores');
    }

    public function edit(int $id): void
    {
        Auth::authorize(['master', 'admin']);

        $model = new Supplier();
        $supplier = $model->find($id);
        $user = new User();
        $users = $user->all();

        if (!$supplier) {
            $_SESSION['error'] = 'Fornecedor não encontrado.';
            redirect($_ENV['APP_BASE'].'/fornecedores');
            return;
        }

        view('fornecedores/form', [
            'title' => 'Editar Produto',
            'supplier' => $supplier,
            'users' => $users
        ]);
    }

    public function update(int $id): void
    {
        Auth::authorize(['master', 'admin']);

        $data = [
            'company_name' => trim($_POST['company_name'])
        ];

        $model = new Supplier();
        $ok = $model->update($id, $data);

        $_SESSION[$ok ? 'success' : 'error'] = $ok
            ? 'Fornecedor atualizado com sucesso!'
            : 'Erro ao atualizar fornecedor.';

        redirect($_ENV['APP_BASE'].'/fornecedores');
    }

    public function delete(int $id): void
    {
        Auth::authorize(['master', 'admin']);

        $model = new Supplier();
        $model->delete($id);

        $_SESSION['success'] = 'Fornecedor removido com sucesso!';
        redirect($_ENV['APP_BASE'].'/fornecedores');
    }

    public function reactivate(int $id): void
    {
        Auth::authorize(['master', 'admin']);

        $model = new Supplier();
        $model->reactivate($id);

        $_SESSION['success'] = 'Fornecedor reativado com sucesso!';
        redirect($_ENV['APP_BASE'].'/fornecedores');
    }

}
