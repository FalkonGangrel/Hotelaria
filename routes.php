<?php
use App\Controllers\LoginController;
use App\Controllers\DashboardController;
use App\Controllers\ProductController;
use App\Controllers\SupplierController;

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

    $requestUri    = rtrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');
    $requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);

    foreach ($routes as $route) {
        // Transforma /produtos/editar/:id → regex /produtos/editar/([^/]+)
        $pattern = preg_replace('#:([\w]+)#', '([^/]+)', $route['uri']);
        $pattern = '#^' . $pattern . '$#';

        if ($route['method'] === $requestMethod && preg_match($pattern, $requestUri, $matches)) {
            array_shift($matches); // remove o match completo
            $action = $route['action'];

            if (is_array($action)) {
                [$controller, $method] = $action;

                if (class_exists($controller)) {
                    $instance = new $controller();

                    // Chama o método com parâmetros da URL (ex: id)
                    call_user_func_array([$instance, $method], $matches);
                    return;
                }
            }

            if (is_callable($action)) {
                call_user_func_array($action, $matches);
                return;
            }

            http_response_code(500);
            echo "Rota inválida para {$requestUri}";
            return;
        }
    }


    // var_dump($requestUri);
    // echo("<br >");
    // var_dump($requestMethod);
    // exit;
    http_response_code(404);
    echo "<h1>404 - Página não encontrada</h1>";
}


/**
 * -----------------------------
 * ROTAS DO SISTEMA
 * -----------------------------
 */

// Index
route('GET', $_ENV['APP_BASE'].'/', [DashboardController::class, 'index']);

// Login
route('GET', $_ENV['APP_BASE'].'/login', [LoginController::class, 'index']);
route('POST', $_ENV['APP_BASE'].'/login/autenticar', [LoginController::class, 'authenticate']);
route('GET', $_ENV['APP_BASE'].'/logout', [LoginController::class, 'logout']);

// Dashboard
route('GET', $_ENV['APP_BASE'].'/dashboard', [DashboardController::class, 'index']);

// Produtos
route('GET', $_ENV['APP_BASE'].'/produtos', [ProductController::class, 'index']);
route('GET', $_ENV['APP_BASE'].'/produtos/novo', [ProductController::class, 'create']);
route('POST', $_ENV['APP_BASE'].'/produtos/salvar', [ProductController::class, 'store']);
route('GET', $_ENV['APP_BASE'].'/produtos/editar/:id', [ProductController::class, 'edit']);
route('POST', $_ENV['APP_BASE'].'/produtos/atualizar/:id', [ProductController::class, 'update']);
route('GET', $_ENV['APP_BASE'].'/produtos/excluir/:id', [ProductController::class, 'delete']);
route('GET', $_ENV['APP_BASE'].'/produtos/reativar/:id', [ProductController::class, 'reactivate']);

// Fornecedores
route('GET', $_ENV['APP_BASE'].'/fornecedores', [SupplierController::class, 'index']);
route('GET', $_ENV['APP_BASE'].'/fornecedores/novo', [SupplierController::class, 'create']);
route('POST', $_ENV['APP_BASE'].'/fornecedores/salvar', [SupplierController::class, 'store']);
route('GET', $_ENV['APP_BASE'].'/fornecedores/editar/:id', [SupplierController::class, 'edit']);
route('POST', $_ENV['APP_BASE'].'/fornecedores/atualizar/:id', [SupplierController::class, 'update']);
route('GET', $_ENV['APP_BASE'].'/fornecedores/excluir/:id', [SupplierController::class, 'delete']);
route('GET', $_ENV['APP_BASE'].'/fornecedores/reativar/:id', [SupplierController::class, 'reactivate']);

/**
 * Dispara o roteamento
 */
dispatch();
