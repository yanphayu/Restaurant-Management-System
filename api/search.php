<?php
header('Content-Type: application/json');
error_reporting(E_ERROR | E_PARSE);
require_once __DIR__ . '/../config/db.php';

$q = trim($_GET['q'] ?? '');
if ($q === '') {
    echo json_encode(['success' => true, 'results' => []]);
    exit;
}

$like = '%' . $q . '%';
$results = [];

// Page shortcuts
$pages = [
    ['label' => 'Dashboard', 'sub' => 'Overview & analytics', 'url' => '/admin/dashboard.php', 'keywords' => 'dashboard overview analytics home summary'],
    ['label' => 'Orders', 'sub' => 'View all orders', 'url' => '/admin/orders/index.php', 'keywords' => 'orders list'],
    ['label' => 'Create Order', 'sub' => 'Place a new order', 'url' => '/admin/orders/create.php', 'keywords' => 'create order new place'],
    ['label' => 'Foods', 'sub' => 'Manage food menu', 'url' => '/admin/foods/index.php', 'keywords' => 'foods menu items dishes'],
    ['label' => 'Categories', 'sub' => 'Manage food categories', 'url' => '/admin/categories/index.php', 'keywords' => 'categories groups types'],
    ['label' => 'Tables', 'sub' => 'Manage restaurant tables', 'url' => '/admin/tables/index.php', 'keywords' => 'tables seating floor map'],
];
$qLower = strtolower($q);
foreach ($pages as $p) {
    if (strpos(strtolower($p['label']), $qLower) !== false || strpos(strtolower($p['keywords']), $qLower) !== false) {
        $results[] = ['type' => 'page', 'label' => $p['label'], 'sub' => $p['sub'], 'url' => $p['url']];
    }
}

try {
    // Orders
    $stmt = $pdo->prepare('SELECT o.order_id, o.total_amount, o.status, o.order_date, rt.table_name FROM orders o JOIN restaurant_tables rt ON o.table_id = rt.table_id WHERE o.order_id LIKE ? OR rt.table_name LIKE ? ORDER BY o.order_id DESC LIMIT 5');
    $stmt->execute([$like, $like]);
    foreach ($stmt->fetchAll() as $r) {
        $dt = date('M d, H:i', strtotime($r['order_date']));
        $results[] = [
            'type' => 'order',
            'label' => '#' . $r['order_id'] . ' - ' . $r['table_name'],
            'sub' => '$' . number_format($r['total_amount'], 2) . ' • ' . $r['status'] . ' • ' . $dt,
            'url' => '/admin/orders/index.php'
        ];
    }

    // Foods
    $stmt = $pdo->prepare('SELECT food_id, food_name, food_price, status FROM foods WHERE food_name LIKE ? LIMIT 5');
    $stmt->execute([$like]);
    foreach ($stmt->fetchAll() as $r) {
        $results[] = [
            'type' => 'food',
            'label' => $r['food_name'],
            'sub' => '$' . number_format($r['food_price'], 2) . ' • ' . $r['status'],
            'url' => '/admin/foods/index.php'
        ];
    }

    // Tables
    $stmt = $pdo->prepare('SELECT table_id, table_name, capacity, status FROM restaurant_tables WHERE table_name LIKE ? LIMIT 5');
    $stmt->execute([$like]);
    foreach ($stmt->fetchAll() as $r) {
        $results[] = [
            'type' => 'table',
            'label' => $r['table_name'],
            'sub' => 'Capacity: ' . $r['capacity'] . ' • ' . $r['status'],
            'url' => '/admin/tables/index.php'
        ];
    }

    // Categories
    $tables = $pdo->query("SHOW TABLES LIKE 'categories'")->fetch();
    if ($tables) {
        $stmt = $pdo->prepare('SELECT category_id, category_name FROM categories WHERE category_name LIKE ? LIMIT 3');
        $stmt->execute([$like]);
        foreach ($stmt->fetchAll() as $r) {
            $results[] = [
                'type' => 'category',
                'label' => $r['category_name'],
                'sub' => 'Category',
                'url' => '/admin/categories/index.php'
            ];
        }
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}

echo json_encode(['success' => true, 'results' => $results]);