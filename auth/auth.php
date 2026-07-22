<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

$action = $_POST['action'] ?? '';

if ($action === 'login') {
    session_start();
    $userName = trim($_POST['user_name'] ?? '');
    $password = $_POST['user_password'] ?? '';

    if ($userName === '' || $password === '') {
        echo json_encode(['success' => false, 'message' => 'Username and password are required']);
        exit;
    }

    $stmt = $pdo->prepare('SELECT * FROM users WHERE user_name = ?');
    $stmt->execute([$userName]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['user_password'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
        exit;
    }

    $_SESSION['user_id']   = $user['user_id'];
    $_SESSION['user_name'] = $user['user_name'];
    $_SESSION['user_role'] = $user['user_role'];

    echo json_encode(['success' => true, 'message' => 'Login successful', 'role' => $user['user_role']]);
    exit;
}

if ($action === 'register') {
    $userName = trim($_POST['user_name'] ?? '');
    $password = $_POST['user_password'] ?? '';

    if ($userName === '' || $password === '') {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }

    try {
        $stmt = $pdo->prepare('SELECT user_id FROM users WHERE user_name = ?');
        $stmt->execute([$userName]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Username already taken']);
            exit;
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (user_name, user_password) VALUES (?, ?)');
        $stmt->execute([$userName, $hashed]);

        echo json_encode(['success' => true, 'message' => 'Account created successfully']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

if ($action === 'logout') {
    session_start();
    $_SESSION = [];
    session_destroy();
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action']);
