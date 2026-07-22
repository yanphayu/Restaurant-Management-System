<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../config/db.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'get_all') {
    $stmt = $pdo->query('SELECT * FROM restaurant_tables ORDER BY table_id');
    $tables = $stmt->fetchAll();

    $stats = [
        'total_capacity' => 0,
        'Occupied'       => 0,
        'Reserved'       => 0,
        'Available'      => 0,
    ];
    foreach ($tables as $t) {
        $stats['total_capacity'] += (int)$t['capacity'];
        if (isset($stats[$t['status']])) $stats[$t['status']]++;
    }

    echo json_encode(['success' => true, 'tables' => $tables, 'stats' => $stats]);
    exit;
}

if ($action === 'get') {
    $id = (int)($_GET['id'] ?? 0);
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Invalid table ID']);
        exit;
    }

    $stmt = $pdo->prepare('SELECT * FROM restaurant_tables WHERE table_id = ?');
    $stmt->execute([$id]);
    $table = $stmt->fetch();

    if (!$table) {
        echo json_encode(['success' => false, 'message' => 'Table not found']);
        exit;
    }

    echo json_encode(['success' => true, 'table' => $table]);
    exit;
}

if ($action === 'create') {
    $name     = trim($_POST['table_name'] ?? '');
    $capacity = (int)($_POST['capacity'] ?? 0);
    $status   = $_POST['status'] ?? 'available';

    if ($name === '' || $capacity < 1) {
        echo json_encode(['success' => false, 'message' => 'Name and capacity are required']);
        exit;
    }

    try {
        $stmt = $pdo->prepare('INSERT INTO restaurant_tables (table_name, capacity, status) VALUES (?, ?, ?)');
        $stmt->execute([$name, $capacity, $status]);
        echo json_encode(['success' => true, 'message' => 'Table created', 'table_id' => $pdo->lastInsertId()]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

if ($action === 'update') {
    $id       = (int)($_POST['table_id'] ?? 0);
    $name     = trim($_POST['table_name'] ?? '');
    $capacity = (int)($_POST['capacity'] ?? 0);
    $status   = $_POST['status'] ?? 'available';

    if (!$id || $name === '' || $capacity < 1) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }

    $stmt = $pdo->prepare('UPDATE restaurant_tables SET table_name = ?, capacity = ?, status = ? WHERE table_id = ?');
    $stmt->execute([$name, $capacity, $status, $id]);

    echo json_encode(['success' => true, 'message' => 'Table updated']);
    exit;
}

if ($action === 'delete') {
    $id = (int)($_POST['table_id'] ?? 0);
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Invalid table ID']);
        exit;
    }

    $stmt = $pdo->prepare('DELETE FROM restaurant_tables WHERE table_id = ?');
    $stmt->execute([$id]);

    echo json_encode(['success' => true, 'message' => 'Table deleted']);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action']);
