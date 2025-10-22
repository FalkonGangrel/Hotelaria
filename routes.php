<?php
// routes.php


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];


// rotas simples — mapeie para controllers
function route(string $path, callable $cb) {
    global $uri;
    if ($uri === $path) {
        $cb();
        exit;
    }
}


// Home
route('/', function() { (new \App\Controllers\DashboardController())->index(); });


// Auth
route('/login', function() {
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
(new \App\Controllers\AuthController())->login();
} else {
(new \App\Controllers\AuthController())->showLogin();
}
});
route('/logout', function(){ (new \App\Controllers\AuthController())->logout(); });


// Products
route('/products', function(){ (new \App\Controllers\ProductController())->index(); });
route('/products/create', function(){ (new \App\Controllers\ProductController())->create(); });
route('/products/store', function(){ (new \App\Controllers\ProductController())->store(); });


// Suppliers
route('/suppliers', function(){ (new \App\Controllers\SupplierController())->index(); });
route('/suppliers/create', function(){ (new \App\Controllers\SupplierController())->create(); });
route('/suppliers/store', function(){ (new \App\Controllers\SupplierController())->store(); });


// Orders
route('/orders', function(){ (new \App\Controllers\OrderController())->index(); });
route('/orders/generate-link', function(){ (new \App\Controllers\OrderController())->generateLink(); });
route('/purchase', function(){ (new \App\Controllers\OrderController())->publicPurchase(); });


// 404 fallback
http_response_code(404); echo "Not Found";