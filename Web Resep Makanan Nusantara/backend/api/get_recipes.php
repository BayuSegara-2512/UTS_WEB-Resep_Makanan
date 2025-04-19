<?php
// ✅ Wajib: Tambahkan header CORS di paling atas sebelum output apapun
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");

require_once '../config/db.php';

$sql = "SELECT * FROM recipes ORDER BY created_at DESC";
$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Gagal fetch recipes: ' . $conn->error]);
    exit;
}

$recipes = [];

while ($row = $result->fetch_assoc()) {
    $id = (int)$row['id'];

    // ✅ Ambil bahan
    $ingredients = [];
    $resIng = $conn->query("SELECT name FROM ingredients WHERE recipe_id = $id");
    if ($resIng) {
        while ($ing = $resIng->fetch_assoc()) {
            $ingredients[] = $ing['name'];
        }
    }

    // ✅ Ambil langkah
    $steps = [];
    $resSteps = $conn->query("SELECT instruction FROM steps WHERE recipe_id = $id ORDER BY step_order ASC");
    if ($resSteps) {
        while ($step = $resSteps->fetch_assoc()) {
            $steps[] = $step['instruction'];
        }
    }

    // ✅ Tambahkan ke array
    $recipes[] = [
        'id'         => $id,
        'title'      => $row['title'],
        'category'   => $row['category'],
        'difficulty' => $row['difficulty'],
        'ingredients'=> $ingredients,
        'steps'      => $steps
    ];
}

// ✅ Kembalikan dalam format JSON
echo json_encode($recipes);
?>
