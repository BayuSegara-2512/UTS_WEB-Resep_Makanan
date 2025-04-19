<?php
require_once '../config/db.php';

class RecipeController {
    // Mendapatkan semua resep
    public function getRecipes() {
        global $conn;
        $sql = "SELECT * FROM recipes ORDER BY created_at DESC";
        $result = $conn->query($sql);
        
        $recipes = [];
        while ($row = $result->fetch_assoc()) {
            $id = (int)$row['id'];
            
            // Ambil bahan
            $ingredients = [];
            $resIng = $conn->query("SELECT name FROM ingredients WHERE recipe_id = $id");
            while ($ing = $resIng->fetch_assoc()) {
                $ingredients[] = $ing['name'];
            }
            
            // Ambil langkah
            $steps = [];
            $resSteps = $conn->query("SELECT instruction FROM steps WHERE recipe_id = $id ORDER BY step_order ASC");
            while ($step = $resSteps->fetch_assoc()) {
                $steps[] = $step['instruction'];
            }
            
            $recipes[] = [
                'id'         => $id,
                'title'      => $row['title'],
                'category'   => $row['category'],
                'difficulty' => $row['difficulty'],
                'ingredients'=> $ingredients,
                'steps'      => $steps
            ];
        }
        
        return $recipes;
    }

    // Menambahkan resep
    public function addRecipe($data) {
        global $conn;

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

            // Insert ingredients
            foreach ($ingredients as $ing) {
                $stmt = $conn->prepare("INSERT INTO ingredients (recipe_id, name) VALUES (?, ?)");
                $stmt->bind_param("is", $recipe_id, $ing);
                $stmt->execute();
            }

            // Insert steps
            foreach ($steps as $index => $step) {
                $stmt = $conn->prepare("INSERT INTO steps (recipe_id, instruction, step_order) VALUES (?, ?, ?)");
                $order = $index + 1;
                $stmt->bind_param("isi", $recipe_id, $step, $order);
                $stmt->execute();
            }

            $conn->commit();
            return ['success' => true, 'message' => 'Resep berhasil ditambahkan'];
        } catch (Exception $e) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Gagal menambahkan resep'];
        }
    }
}
?>
