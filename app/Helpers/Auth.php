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
            $url = $_ENV['APP_BASE'].'/login';
            $_SESSION['error'] = 'Você precisa estar logado para acessar esta página.';
            header('Location: ' . $url);
            exit;
        }

        $user = $_SESSION['user'];

        // Se não houver restrição de papel, apenas precisa estar logado
        if (empty($roles)) {
            return true;
        }

        // Verifica se o papel do usuário está na lista permitida
        if (!in_array($user['role'], $roles)) {
            $url = $_ENV['APP_BASE'].'/dashboard';
            $_SESSION['error'] = 'Você não tem permissão para acessar esta área.';
            header('Location: ' . $url);
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
        $url = $_ENV['APP_BASE'].'/dashboard';
        header('Location: '. $url);
        exit;
    }
}
