<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../config/db.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'get_stats') {
    $today = date('Y-m-d');

    // Total orders
    $stmt = $pdo->query('SELECT COUNT(*) as cnt FROM orders');
    $totalOrders = $stmt->fetch()['cnt'];

    // Total revenue (all time)
    $stmt = $pdo->query('SELECT COALESCE(SUM(total_amount), 0) as rev FROM orders');
    $totalRevenue = $stmt->fetch()['rev'];

    // Active tables (Occupied)
    $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM restaurant_tables WHERE status = 'Occupied'");
    $activeTables = $stmt->fetch()['cnt'];

    // Most popular food
    $stmt = $pdo->query('SELECT f.food_name, COUNT(od.order_id) as order_count FROM order_details od JOIN foods f ON od.food_id = f.food_id GROUP BY f.food_id ORDER BY order_count DESC LIMIT 1');
    $popular = $stmt->fetch();
    $popularFood = $popular ? $popular['food_name'] : '--';

    // Today's revenue
    $stmt = $pdo->prepare('SELECT COALESCE(SUM(total_amount), 0) as rev FROM orders WHERE DATE(order_date) = ?');
    $stmt->execute([$today]);
    $todayRevenue = $stmt->fetch()['rev'];

    // Today's orders
    $stmt = $pdo->prepare('SELECT COUNT(*) as cnt FROM orders WHERE DATE(order_date) = ?');
    $stmt->execute([$today]);
    $todayOrders = $stmt->fetch()['cnt'];

    echo json_encode([
        'success' => true,
        'totalOrders' => (int)$totalOrders,
        'totalRevenue' => (float)$totalRevenue,
        'activeTables' => (int)$activeTables,
        'popularFood' => $popularFood,
        'todayRevenue' => (float)$todayRevenue,
        'todayOrders' => (int)$todayOrders
    ]);
    exit;
}

if ($action === 'get_top') {
    $stmt = $pdo->query('
        SELECT f.food_name, COUNT(od.order_id) as order_count, SUM(od.subtotal) as revenue
        FROM order_details od 
        JOIN foods f ON od.food_id = f.food_id 
        GROUP BY f.food_id 
        ORDER BY order_count DESC 
        LIMIT 5
    ');
    echo json_encode(['success' => true, 'foods' => $stmt->fetchAll()]);
    exit;
}

if ($action === 'get_recent') {
    $stmt = $pdo->query('SELECT o.*, rt.table_name, u.user_name FROM orders o JOIN restaurant_tables rt ON o.table_id = rt.table_id JOIN users u ON o.user_id = u.user_id ORDER BY o.order_id DESC LIMIT 5');
    $orders = $stmt->fetchAll();

    foreach ($orders as &$o) {
        $stmt2 = $pdo->prepare('SELECT od.*, f.food_name FROM order_details od JOIN foods f ON od.food_id = f.food_id WHERE od.order_id = ?');
        $stmt2->execute([$o['order_id']]);
        $o['items'] = $stmt2->fetchAll();
    }

    echo json_encode(['success' => true, 'orders' => $orders]);
    exit;
}

if ($action === 'get_daily') {
    $labels = [];
    $orderCounts = [];
    $revenues = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-{$i} days"));
        $dayLabel = date('D', strtotime($date));
        $labels[] = $dayLabel;

        $stmt = $pdo->prepare('SELECT COUNT(*) as cnt, COALESCE(SUM(total_amount), 0) as rev FROM orders WHERE DATE(order_date) = ?');
        $stmt->execute([$date]);
        $row = $stmt->fetch();
        $orderCounts[] = (int)$row['cnt'];
        $revenues[] = (float)$row['rev'];
    }
    echo json_encode(['success' => true, 'labels' => $labels, 'orders' => $orderCounts, 'revenues' => $revenues]);
    exit;
}

if ($action === 'get_status_breakdown') {
    $stmt = $pdo->query("SELECT status, COUNT(*) as cnt FROM orders GROUP BY status");
    $rows = $stmt->fetchAll();
    $labels = [];
    $data = [];
    foreach ($rows as $r) {
        $labels[] = $r['status'];
        $data[] = (int)$r['cnt'];
    }
    echo json_encode(['success' => true, 'labels' => $labels, 'data' => $data]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action']);