<?php

namespace App\Helpers;

class Auth
{
    /**
     * Verifica se o usuário está logado e tem permissão.
     *
     * @param array $roles Perfis permitidos (ex: ['master', 'admin'])
     * @return bool
     */
    public static function authorize(array $roles = []): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Se não estiver logado
        if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
            $_SESSION['error'] = 'Você precisa estar logado para acessar esta página.';
            header('Location: ' . ($_ENV['APP_BASE'] ?? '') . '/login');
            exit;
        }

        $user = $_SESSION['user'];

        // Se não houver restrição de papel, apenas precisa estar logado
        if (empty($roles)) {
            return true;
        }

        /**
         * 🔎 Compatibilidade:
         * - $user['role_name'] → nome vindo do JOIN com tabela roles
         * - $user['role'] → usado apenas por compatibilidade retroativa
         */
        $roleName = $user['role_name'] ?? $user['role'] ?? null;

        if (!$roleName || !in_array($roleName, $roles)) {
            $_SESSION['error'] = 'Você não tem permissão para acessar esta área.';
            header('Location: ' . ($_ENV['APP_BASE'] ?? '') . '/dashboard');
            exit;
        }

        return true;
    }

    /**
     * Retorna o usuário autenticado
     */
    public static function user(): ?array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

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

        $_SESSION = [];
        session_destroy();

        header('Location: ' . ($_ENV['APP_BASE'] ?? '') . '/login');
        exit;
    }
}
