<?php
function validateRecipe($data) {
    $errors = [];

    if (empty($data['title'])) $errors[] = "Judul resep wajib diisi.";
    if (empty($data['category'])) $errors[] = "Kategori wajib diisi.";
    if (empty($data['difficulty'])) $errors[] = "Tingkat kesulitan wajib diisi.";

    if (!isset($data['ingredients']) || count($data['ingredients']) < 1) {
        $errors[] = "Minimal 1 bahan diperlukan.";
    }

    if (!isset($data['steps']) || count($data['steps']) < 1) {
        $errors[] = "Minimal 1 langkah diperlukan.";
    }

    return $errors;
}
