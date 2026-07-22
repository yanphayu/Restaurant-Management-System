<?php

header('Content-Type: application/json');

require_once '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];

try {

    if ($method === 'GET' && ($_GET['action'] ?? '') === 'get') {

        $id = intval($_GET['id'] ?? 0);
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Food ID is required']);
            exit;
        }

        $stmt = $pdo->prepare("
            SELECT f.*, c.category_name, c.category_icon
            FROM foods f
            LEFT JOIN categories c ON f.category_id = c.category_id
            WHERE f.food_id = ?
        ");
        $stmt->execute([$id]);
        $food = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'data' => $food
        ]);

    } elseif ($method === 'GET') {

        $stmt = $pdo->query("
            SELECT f.*, c.category_name, c.category_icon
            FROM foods f
            LEFT JOIN categories c ON f.category_id = c.category_id
            ORDER BY f.food_id DESC
        ");

        echo json_encode([
            'success' => true,
            'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
        ]);

    } elseif ($method === 'POST') {

        $id = intval($_POST['food_id'] ?? 0);
        $name = $_POST['food_name'] ?? '';
        $category_id = $_POST['category_id'] ?? 0;
        $description = $_POST['food_description'] ?? '';
        $price = $_POST['food_price'] ?? 0;
        $status = ucfirst($_POST['status'] ?? 'available');

        if (!$name || !$category_id) {
            echo json_encode(['success' => false, 'message' => 'Food name and category are required']);
            exit;
        }

        $image = null;

        if (!empty($_FILES['food_image']['name'])) {
            $uploadDir = __DIR__ . '/../uploads';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $ext = strtolower(pathinfo($_FILES['food_image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (!in_array($ext, $allowed)) {
                echo json_encode(['success' => false, 'message' => 'Invalid image type. Allowed: jpg, jpeg, png, gif, webp']);
                exit;
            }

            if ($_FILES['food_image']['size'] > 5 * 1024 * 1024) {
                echo json_encode(['success' => false, 'message' => 'Image size exceeds 5MB limit']);
                exit;
            }

            if ($_FILES['food_image']['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(['success' => false, 'message' => 'Upload error: ' . $_FILES['food_image']['error']]);
                exit;
            }

            $image = time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;

            if (!move_uploaded_file($_FILES['food_image']['tmp_name'], $uploadDir . '/' . $image)) {
                echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file to: ' . $uploadDir]);
                exit;
            }
        }

        if ($id) {

            if ($image) {
                $stmt = $pdo->prepare("
                    UPDATE foods
                    SET category_id=?, food_name=?, food_description=?, food_price=?, food_image=?, status=?
                    WHERE food_id=?
                ");
                $stmt->execute([$category_id, $name, $description, $price, $image, $status, $id]);
            } else {
                $stmt = $pdo->prepare("
                    UPDATE foods
                    SET category_id=?, food_name=?, food_description=?, food_price=?, status=?
                    WHERE food_id=?
                ");
                $stmt->execute([$category_id, $name, $description, $price, $status, $id]);
            }

            echo json_encode([
                'success' => true,
                'message' => 'Food updated successfully'
            ]);

        } else {

            $stmt = $pdo->prepare("
                INSERT INTO foods (category_id, food_name, food_description, food_price, food_image, status)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$category_id, $name, $description, $price, $image ?? '', $status]);

            echo json_encode([
                'success' => true,
                'message' => 'Food created successfully',
                'food_id' => $pdo->lastInsertId()
            ]);

        }

    } elseif ($method === 'PUT') {

        $input = json_decode(file_get_contents('php://input'), true);

        $id = $input['food_id'] ?? 0;
        $name = trim($input['food_name'] ?? '');
        $category_id = $input['category_id'] ?? 0;
        $description = trim($input['food_description'] ?? '');
        $price = $input['food_price'] ?? 0;
        $status = ucfirst($input['status'] ?? 'available');

        if (!$id || !$name || !$category_id) {
            echo json_encode(['success' => false, 'message' => 'Food ID, name, and category are required']);
            exit;
        }

        $stmt = $pdo->prepare("
            UPDATE foods
            SET category_id=?, food_name=?, food_description=?, food_price=?, status=?
            WHERE food_id=?
        ");
        $stmt->execute([$category_id, $name, $description, $price, $status, $id]);

        echo json_encode([
            'success' => true,
            'message' => 'Food updated successfully'
        ]);

    } elseif ($method === 'DELETE') {

        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['food_id'] ?? $_GET['food_id'] ?? 0;

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Food ID is required']);
            exit;
        }

        $stmt = $pdo->prepare("DELETE FROM foods WHERE food_id=?");
        $stmt->execute([$id]);

        echo json_encode([
            'success' => true,
            'message' => 'Food deleted successfully'
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
