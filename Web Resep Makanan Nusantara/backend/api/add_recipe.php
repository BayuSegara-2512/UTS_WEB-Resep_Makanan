<?php
// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");

// TANGANI PRE-FLIGHT (OPTIONS) REQUEST
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once '../config/db.php';
require_once '../middlewares/validate_recipe.php';

$data = json_decode(file_get_contents("php://input"), true);

// Validasi
$errors = validateRecipe($data);
if (count($errors) > 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

// Simpan ke DB
$title = $data['title'];
$category = $data['category'];
$difficulty = $data['difficulty'];
$ingredients = $data['ingredients'];
$steps = $data['steps'];

$conn->begin_transaction();

try {
    $stmt = $conn->prepare("INSERT INTO recipes (title, category, difficulty) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $category, $difficulty);
    $stmt->execute();
    $recipe_id = $stmt->insert_id;

    foreach ($ingredients as $ing) {
        $stmt = $conn->prepare("INSERT INTO ingredients (recipe_id, name) VALUES (?, ?)");
        $stmt->bind_param("is", $recipe_id, $ing);
        $stmt->execute();
    }

    foreach ($steps as $index => $step) {
        $stmt = $conn->prepare("INSERT INTO steps (recipe_id, instruction, step_order) VALUES (?, ?, ?)");
        $order = $index + 1;
        $stmt->bind_param("isi", $recipe_id, $step, $order);
        $stmt->execute();
    }

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Resep berhasil ditambahkan']);
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal menambahkan resep']);
}
