<?php
    session_start();

    if (! isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    require_once 'db.php';

    $category_id = (int) $_GET['id']; // Get the category ID from the URL

    // Fetch the category to check if it exists
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$category_id]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    if (! $category) {
        // If category does not exist, redirect to manage categories
        header('Location: categories.php');
        exit;
    }

    // Check if there are any menu items associated with the category
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM menu_items WHERE category_id = ?");
    $stmt->execute([$category_id]);
    $menu_item_count = $stmt->fetchColumn();

    $errors  = [];
    $success = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Force delete the category and all associated menu items
        if ($menu_item_count > 0) {
            // Delete all associated menu items first
            $stmt = $pdo->prepare("DELETE FROM menu_items WHERE category_id = ?");
            $stmt->execute([$category_id]);

            // Now delete the category
            $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
            $stmt->execute([$category_id]);

            $success = true;
        } else {
            // Just delete the category if no menu items are present
            $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
            $stmt->execute([$category_id]);

            $success = true;
        }
    }
?>

<?php include_once 'includes/header.php'; ?>
<div class="container mt-5">
    <h2>Supprimer la Catégorie</h2>

    <?php if ($menu_item_count > 0): ?>
        <div class="alert alert-warning">
            <strong>Attention !</strong> Cette catégorie est utilisée dans <strong><?php echo $menu_item_count; ?></strong> élément(s) de menu. Si vous continuez, tous les éléments de menu associés seront supprimés.
            <form method="POST">
                <button type="submit" class="btn btn-danger mt-3">Forcer la suppression de cette catégorie</button>
            </form>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            Il n'y a aucun élément de menu associé à cette catégorie. Vous pouvez la supprimer sans danger.
            <form method="POST">
                <button type="submit" class="btn btn-danger mt-3">Supprimer la Catégorie</button>
            </form>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success mt-3">
            La catégorie a été supprimée avec succès, ainsi que tous les éléments de menu associés.
        </div>
        <a href="categories.php" class="btn btn-primary mt-3">Retour à la gestion des catégories</a>
    <?php endif; ?>
</div>
<?php include_once 'includes/footer.php'; ?>
