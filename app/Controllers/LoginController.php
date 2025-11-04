<?php

namespace App\Controllers;

use App\Models\User;
use App\Helpers\Auth;

class LoginController
{
    public function index()
    {
        // Se já estiver logado, redireciona
        if (isset($_SESSION['user'])) {
            header('Location: /dashboard');
            exit;
        }

        view('auth/login', [
            'title' => 'Login'
        ]);
    }

    public function authenticate()
    {
        $email = trim($_POST['email'] ?? '');
        $senha = trim($_POST['senha'] ?? '');

        if (empty($email) || empty($senha)) {
            $_SESSION['error'] = 'Preencha todos os campos.';
            return redirect($_ENV['APP_BASE'].'/login');
        }

        $user = User::findByEmail($email);

        if (!$user || !password_verify($senha, $user['password'])) {
            $_SESSION['error'] = 'Usuário ou senha inválidos.';
            return redirect($_ENV['APP_BASE'].'/login');
        }

        // Armazena dados mínimos na sessão
        $_SESSION['user'] = [
            'id' => $user['id'],
            'nome' => $user['name'],
            'email' => $user['email'],
            'role_name' => $user['role'],
            'role_id' => $user['role_id']
        ];

        $_SESSION['success'] = 'Bem-vindo, ' . $user['name'] . '!';
        return redirect($_ENV['APP_BASE'].'/dashboard');
    }

    public function logout()
    {
        Auth::logout();
    }
}
