<?php

header('Content-Type: application/json');

require_once '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];

try {

    if ($method === 'GET') {

        $stmt = $pdo->query("
            SELECT category_id,
                   category_name,
                   category_icon,
                   created_at
            FROM categories
            ORDER BY category_id DESC
        ");

        echo json_encode([
            'success' => true,
            'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
        ]);

    } elseif ($method === 'POST') {

        $input = json_decode(file_get_contents('php://input'), true);

        $category_name = trim($input['category_name'] ?? '');
        $category_icon = trim($input['category_icon'] ?? 'restaurant');

        if ($category_name === '') {
            echo json_encode(['success' => false, 'message' => 'Category name is required']);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO categories (category_name, category_icon) VALUES (:category_name, :category_icon)");
        $stmt->execute(['category_name' => $category_name, 'category_icon' => $category_icon]);

        echo json_encode([
            'success' => true,
            'message' => 'Category created successfully',
            'category_id' => $pdo->lastInsertId()
        ]);

    } elseif ($method === 'PUT') {

        $input = json_decode(file_get_contents('php://input'), true);

        $category_id = $input['category_id'] ?? null;
        $category_name = trim($input['category_name'] ?? '');
        $category_icon = trim($input['category_icon'] ?? 'restaurant');

        if (!$category_id || $category_name === '') {
            echo json_encode(['success' => false, 'message' => 'Category ID and name are required']);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE categories SET category_name = :category_name, category_icon = :category_icon WHERE category_id = :category_id");
        $stmt->execute([
            'category_name' => $category_name,
            'category_icon' => $category_icon,
            'category_id' => $category_id
        ]);

        if ($stmt->rowCount() === 0) {
            echo json_encode(['success' => false, 'message' => 'Category not found or no changes made']);
            exit;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Category updated successfully'
        ]);

    } elseif ($method === 'DELETE') {

        $input = json_decode(file_get_contents('php://input'), true);
        $category_id = $input['category_id'] ?? $_GET['category_id'] ?? null;

        if (!$category_id) {
            echo json_encode(['success' => false, 'message' => 'Category ID is required']);
            exit;
        }

        $stmt = $pdo->prepare("DELETE FROM categories WHERE category_id = :category_id");
        $stmt->execute(['category_id' => $category_id]);

        if ($stmt->rowCount() === 0) {
            echo json_encode(['success' => false, 'message' => 'Category not found']);
            exit;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
