<?php
// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Content-Type: application/json");

require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (isset($data['id'])) {
        $recipe_id = (int)$data['id'];

        // Hapus bahan resep
        $stmt = $conn->prepare("DELETE FROM ingredients WHERE recipe_id = ?");
        $stmt->bind_param("i", $recipe_id);
        $stmt->execute();

        // Hapus langkah resep
        $stmt = $conn->prepare("DELETE FROM steps WHERE recipe_id = ?");
        $stmt->bind_param("i", $recipe_id);
        $stmt->execute();

        // Hapus resep
        $stmt = $conn->prepare("DELETE FROM recipes WHERE id = ?");
        $stmt->bind_param("i", $recipe_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Resep berhasil dihapus']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Resep tidak ditemukan']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID resep tidak valid']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan']);
}
