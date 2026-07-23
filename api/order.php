<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../config/db.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'get_all') {
    $stmt = $pdo->query('SELECT o.*, rt.table_name, u.user_name FROM orders o JOIN restaurant_tables rt ON o.table_id = rt.table_id JOIN users u ON o.user_id = u.user_id ORDER BY o.order_id DESC');
    $orders = $stmt->fetchAll();

    foreach ($orders as &$o) {
        $stmt2 = $pdo->prepare('SELECT od.*, f.food_name FROM order_details od JOIN foods f ON od.food_id = f.food_id WHERE od.order_id = ?');
        $stmt2->execute([$o['order_id']]);
        $o['items'] = $stmt2->fetchAll();
    }

    $stats = ['active' => 0, 'preparing' => 0, 'revenue' => 0];
    $stmt3 = $pdo->query("SELECT COUNT(*) as cnt FROM orders WHERE status = 'Pending'");
    $stats['active'] = $stmt3->fetch()['cnt'];
    $stmt4 = $pdo->query("SELECT COUNT(*) as cnt FROM orders WHERE status = 'Completed'");
    $stats['preparing'] = $stmt4->fetch()['cnt'];
    $today = date('Y-m-d');
    $stmt5 = $pdo->prepare("SELECT COALESCE(SUM(total_amount), 0) as rev FROM orders WHERE DATE(order_date) = ?");
    $stmt5->execute([$today]);
    $stats['revenue'] = $stmt5->fetch()['rev'];

    echo json_encode(['success' => true, 'orders' => $orders, 'stats' => $stats]);
    exit;
}

if ($action === 'get') {
    $id = (int)($_GET['id'] ?? 0);
    if (!$id) { echo json_encode(['success' => false, 'message' => 'Invalid order ID']); exit; }

    $stmt = $pdo->prepare('SELECT o.*, rt.table_name, u.user_name FROM orders o JOIN restaurant_tables rt ON o.table_id = rt.table_id JOIN users u ON o.user_id = u.user_id WHERE o.order_id = ?');
    $stmt->execute([$id]);
    $order = $stmt->fetch();
    if (!$order) { echo json_encode(['success' => false, 'message' => 'Order not found']); exit; }

    $stmt2 = $pdo->prepare('SELECT od.*, f.food_name, f.food_image FROM order_details od JOIN foods f ON od.food_id = f.food_id WHERE od.order_id = ?');
    $stmt2->execute([$id]);
    $order['items'] = $stmt2->fetchAll();

    echo json_encode(['success' => true, 'order' => $order]);
    exit;
}

if ($action === 'create') {
    $tableId = (int)($_POST['table_id'] ?? 0);
    $items   = json_decode($_POST['items'] ?? '[]', true);
    $userId  = $_SESSION['user_id'] ?? 0;

    if (!$tableId || !$userId || empty($items)) {
        echo json_encode(['success' => false, 'message' => 'Table, items, and login are required. user_id=' . $userId]);
        exit;
    }

    $checkUser = $pdo->prepare('SELECT user_id FROM users WHERE user_id = ?');
    $checkUser->execute([$userId]);
    if (!$checkUser->fetch()) {
        echo json_encode(['success' => false, 'message' => 'User not found. Please login again. user_id=' . $userId]);
        exit;
    }

    try {
        $pdo->beginTransaction();

        $totalAmount = 0;
        foreach ($items as $item) {
            $totalAmount += ($item['price'] * $item['quantity']);
        }

        $stmt = $pdo->prepare('INSERT INTO orders (user_id, table_id, total_amount) VALUES (?, ?, ?)');
        $stmt->execute([$userId, $tableId, $totalAmount]);
        $orderId = $pdo->lastInsertId();

        $stmt2 = $pdo->prepare('INSERT INTO order_details (order_id, food_id, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?)');
        foreach ($items as $item) {
            $sub = $item['price'] * $item['quantity'];
            $stmt2->execute([$orderId, $item['food_id'], $item['quantity'], $item['price'], $sub]);
        }

        $stmt3 = $pdo->prepare('UPDATE restaurant_tables SET status = ? WHERE table_id = ?');
        $stmt3->execute(['Occupied', $tableId]);

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Order created', 'order_id' => $orderId]);
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

if ($action === 'update_status') {
    $id     = (int)($_POST['order_id'] ?? 0);
    $status = $_POST['status'] ?? '';
    $valid  = ['Pending', 'Completed', 'Paid'];

    if (!$id || !in_array($status, $valid)) {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        exit;
    }

    try {
        $stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE order_id = ?');
        $stmt->execute([$status, $id]);

        if ($status === 'Paid' || $status === 'Completed') {
            $getTbl = $pdo->prepare('SELECT table_id FROM orders WHERE order_id = ?');
            $getTbl->execute([$id]);
            $tbl = $getTbl->fetch();
            if ($tbl) {
                $checkActive = $pdo->prepare("SELECT order_id FROM orders WHERE table_id = ? AND status = 'Pending' AND order_id != ?");
                $checkActive->execute([$tbl['table_id'], $id]);
                if (!$checkActive->fetch()) {
                    $stmt2 = $pdo->prepare('UPDATE restaurant_tables SET status = ? WHERE table_id = ?');
                    $stmt2->execute(['Available', $tbl['table_id']]);
                }
            }
        }

        echo json_encode(['success' => true, 'message' => 'Status updated']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

if ($action === 'delete') {
    $id = (int)($_POST['order_id'] ?? 0);
    if (!$id) { echo json_encode(['success' => false, 'message' => 'Invalid order ID']); exit; }

    try {
        $pdo->beginTransaction();

        $getTbl = $pdo->prepare('SELECT table_id FROM orders WHERE order_id = ?');
        $getTbl->execute([$id]);
        $tbl = $getTbl->fetch();
        if ($tbl) {
            $stmt0 = $pdo->prepare('UPDATE restaurant_tables SET status = ? WHERE table_id = ?');
            $stmt0->execute(['Available', $tbl['table_id']]);
        }

        $stmt = $pdo->prepare('DELETE FROM order_details WHERE order_id = ?');
        $stmt->execute([$id]);
        $stmt2 = $pdo->prepare('DELETE FROM orders WHERE order_id = ?');
        $stmt2->execute([$id]);
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Order deleted']);
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

if ($action === 'get_tables') {
    $stmt = $pdo->query("SELECT table_id, table_name FROM restaurant_tables WHERE status = 'Available' ORDER BY table_name");
    echo json_encode(['success' => true, 'tables' => $stmt->fetchAll()]);
    exit;
}

if ($action === 'get_foods') {
    $stmt = $pdo->query("SELECT f.*, c.category_name FROM foods f JOIN categories c ON f.category_id = c.category_id WHERE f.status = 'Available' ORDER BY f.food_name");
    echo json_encode(['success' => true, 'foods' => $stmt->fetchAll()]);
    exit;
}

if ($action === 'get_categories') {
    $stmt = $pdo->query('SELECT * FROM categories ORDER BY category_name');
    echo json_encode(['success' => true, 'categories' => $stmt->fetchAll()]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action']);
