<?php
use App\Models\Menu;
use App\Helpers\Auth;

$base = rtrim($_ENV['APP_BASE'] ?? '', '/'); // Ex: "/meuapp"
$requestUri = strtok($_SERVER['REQUEST_URI'], '?') ?: '/';

// Remove base prefix
if (!empty($base) && str_starts_with($requestUri, $base)) {
    $current = substr($requestUri, strlen($base));
    if ($current === '') $current = '/';
} else {
    $current = $requestUri;
}
$current = '/' . ltrim($current, '/'); // Normaliza

$roleId = Auth::user()['role_id'] ?? null;
$menuTree = [];

if ($roleId) {
    $menuModel = new Menu();
    $menus = $menuModel->getMenusByRole($roleId);

    foreach ($menus as $menu) {
        $menu['route'] = '/' . ltrim(($menu['route'] ?? '/'), '/');
        $menu['children'] = $menu['children'] ?? [];
        $menu['is_active'] = false;
        $menuTree[$menu['id']] = $menu;
    }

    // Monta árvore
    $tree = [];
    foreach ($menuTree as $id => $menu) {
        if (!empty($menu['parent_id']) && isset($menuTree[$menu['parent_id']])) {
            $menuTree[$menu['parent_id']]['children'][] = &$menuTree[$id];
        } else {
            $tree[$id] = &$menuTree[$id];
        }
    }

    // Função de comparação com suporte a parâmetros (:id, :slug, etc)
    $isRouteActive = function (string $route, string $current): bool {
        $route = '/' . ltrim($route, '/');
        $current = '/' . ltrim($current, '/');

        // Rota exata
        if ($route === $current) {
            return true;
        }

        // Se for rota raiz
        if ($route === '/') {
            return $current === '/';
        }

        // Substitui :param por regex genérico
        $pattern = preg_replace('/:([\w]+)/', '([^/]+)', $route);
        $pattern = '#^' . preg_quote(trim($pattern, '/'), '#') . '$#';
        $pattern = str_replace(preg_quote('([^/]+)', '#'), '([^/]+)', $pattern);

        // Compara sem quebrar por subdiretório
        return preg_match('#^' . trim($pattern, '#') . '(/|$)#', trim($current, '/')) === 1;
    };

    // Marca recursivamente
    $markActive = function (array &$node) use (&$markActive, $isRouteActive, $current) {
        $active = $isRouteActive($node['route'] ?? '/', $current);

        if (!empty($node['children'])) {
            foreach ($node['children'] as &$child) {
                if ($markActive($child)) $active = true;
            }
        }

        $node['is_active'] = $active;
        return $active;
    };

    foreach ($tree as &$root) {
        $markActive($root);
    }
}
?>

<nav class="sidebar">
    <ul class="list-unstyled">
        <?php if (!empty($tree)): ?>
            <?php foreach ($tree as $menu): ?>
                <li>
                    <?php
                        $href = htmlspecialchars(($menu['route'] && $menu['route'] !== '/' ? ($base . $menu['route']) : ($base ?: '/')));
                        $aClass = $menu['is_active'] ? 'active' : '';
                    ?>
                    <a href="<?= $href ?>" class="<?= $aClass ?>">
                        <?php if (!empty($menu['icon'])): ?>
                            <i class="bi bi-<?= htmlspecialchars($menu['icon']) ?> me-2"></i>
                        <?php endif; ?>
                        <?= htmlspecialchars($menu['name']) ?>
                    </a>

                    <?php if (!empty($menu['children'])): ?>
                        <ul>
                            <?php foreach ($menu['children'] as $child): ?>
                                <?php
                                    $chHref = htmlspecialchars(($child['route'] && $child['route'] !== '/' ? ($base . $child['route']) : ($base ?: '/')));
                                    $chClass = $child['is_active'] ? 'active' : '';
                                ?>
                                <li>
                                    <a href="<?= $chHref ?>" class="<?= $chClass ?>">
                                        <?= htmlspecialchars($child['name']) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li><a href="<?= $_ENV['APP_BASE'] ?? '/' ?>">Dashboard</a></li>
        <?php endif; ?>
    </ul>
</nav>
