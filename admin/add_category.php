<?php
require_once 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';

    // Validate category name
    if (empty($name)) {
        $errors[] = 'Le nom de la catégorie est requis.';
    } else {
        // Check if category name already exists
        $stmt = $pdo->prepare("SELECT * FROM categories WHERE name = ?");
        $stmt->execute([$name]);
        $existing_category = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($existing_category) {
            $errors[] = "Cette catégorie existe déjà.";
        } else {
            try {
                // Insert category into categories table
                $stmt = $pdo->prepare("
                    INSERT INTO categories (name, created_at)
                    VALUES (?, NOW())
                ");
                $stmt->execute([$name]);

                $success = true;
                echo '<script>window.location.href = "categories.php";</script>';

            } catch (Exception $e) {
                $errors[] = "Une erreur s'est produite lors de l'ajout de la catégorie. Veuillez réessayer.";
            }
        }
    }
}
