<?php
    session_start();

    if (! isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    require_once 'db.php';

    $errors  = [];
    $success = false;

    if (! isset($_GET['id'])) {
        header('Location: categories.php');
        exit;
    }

    $id = (int) $_GET['id'];

    // Get current category
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    if (! $category) {
        header('Location: categories.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'] ?? '';

        if (empty($name)) {
            $errors[] = 'Le nom de la catégorie est requis.';
        } else {
            // Check for duplicate (excluding current category)
            $stmt = $pdo->prepare("SELECT * FROM categories WHERE name = ? AND id != ?");
            $stmt->execute([$name, $id]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                $errors[] = 'Une autre catégorie porte déjà ce nom.';
            } else {
                // Update
                try {
                    $stmt = $pdo->prepare("UPDATE categories SET name = ? WHERE id = ?");
                    $stmt->execute([$name, $id]);
                    $success = true;

                    // Refresh category data
                    $category['name'] = $name;
                } catch (Exception $e) {
                    $errors[] = "Une erreur est survenue lors de la mise à jour.";
                }
            }
        }
    }
?>
<?php include_once 'includes/header.php'; ?>
<div class="container mt-5">
    <h2>Modifier la Catégorie</h2>
    <?php if (! empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
<?php if ($success): ?>
        <div class="alert alert-success">
            La catégorie a été mise à jour avec succès.
        </div>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group mb-3">
            <label for="name">Nom de la Catégorie *</label>
            <input type="text" class="form-control" name="name" id="name" value="<?php echo htmlspecialchars($category['name']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="categories.php" class="btn btn-secondary">Retour</a>
    </form>
</div>
<?php include_once 'includes/footer.php'; ?>
