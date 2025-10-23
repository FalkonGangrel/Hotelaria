<?php

namespace App\Helpers;

class Auth
{
    /**
     * Verifica se o usuário está logado e tem permissão.
     * 
     * @param array $roles Perfis permitidos (ex: ['master', 'admin'])
     */
    public static function authorize(array $roles = []): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Você precisa estar logado para acessar esta página.';
            header('Location: /login');
            exit;
        }

        $user = $_SESSION['user'];

        // Se não houver restrição de papel, apenas precisa estar logado
        if (empty($roles)) {
            return true;
        }

        // Verifica se o papel do usuário está na lista permitida
        if (!in_array($user['role'], $roles)) {
            $_SESSION['error'] = 'Você não tem permissão para acessar esta área.';
            header('Location: /dashboard');
            exit;
        }

        return true;
    }

    /**
     * Retorna o usuário autenticado
     */
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Faz logout do usuário
     */
    public static function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        unset($_SESSION['user']);
        session_destroy();
        header('Location: /login');
        exit;
    }
}
