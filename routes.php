<?php
use App\Controllers\LoginController;
use App\Controllers\ProductController;
use App\Controllers\DashboardController;

/**
 * Sistema simples de rotas com suporte a múltiplas rotas e métodos HTTP.
 */

$routes = [];

/**
 * Registra uma rota
 */
function route(string $method, string $uri, $action): void
{
    global $routes;
    $routes[] = [
        'method' => strtoupper($method),
        'uri'    => rtrim($uri, '/'),
        'action' => $action
    ];
}

/**
 * Inicia o roteamento
 */
function dispatch(): void
{
    global $routes;

    $requestUri    = strtok($_SERVER['REQUEST_URI'], '?');
    $requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);

    foreach ($routes as $route) {
        if ($route['uri'] === rtrim($requestUri, '/') && $route['method'] === $requestMethod) {
            $action = $route['action'];

            if (is_array($action)) {
                [$controller, $method] = $action;
                if (class_exists($controller)) {
                    (new $controller)->$method();
                    return;
                }
            }

            if (is_callable($action)) {
                $action();
                return;
            }

            http_response_code(500);
            echo "Rota inválida para {$requestUri}";
            return;
        }
    }
    var_dump($routes);
    echo "<br>";
    var_dump($requestUri);
    echo "<br>";
    var_dump($requestMethod);
    // http_response_code(404);
    // echo "<h1>404 - Página não encontrada</h1>";
}

/**
 * -----------------------------
 * ROTAS DO SISTEMA
 * -----------------------------
 */

// Index
route('GET', '/', [DashboardController::class, 'index']);

// Login
route('GET', '/login', [LoginController::class, 'index']);
route('POST', '/login', [LoginController::class, 'authenticate']);
route('GET', '/logout', [LoginController::class, 'logout']);

// Dashboard
route('GET', '/dashboard', [DashboardController::class, 'index']);

// Produtos
route('GET', '/produtos', [ProductController::class, 'index']);
route('GET', '/produtos/novo', [ProductController::class, 'create']);
route('POST', '/produtos/salvar', [ProductController::class, 'store']);
route('GET', '/produtos/editar', [ProductController::class, 'edit']);
route('POST', '/produtos/atualizar', [ProductController::class, 'update']);
route('GET', '/produtos/excluir', [ProductController::class, 'delete']);

/**
 * Dispara o roteamento
 */
dispatch();
