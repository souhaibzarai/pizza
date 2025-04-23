<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'db.php'; // Include database connection

// Fetch promotion data based on ID
$promotion_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($promotion_id > 0) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM promotions WHERE id = ?");
        $stmt->execute([$promotion_id]);
        $promotion = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$promotion) {
            die("Promotion introuvable.");
        }
    } catch (PDOException $e) {
        die("Erreur lors de la récupération de la promotion : " . $e->getMessage());
    }
} else {
    die("ID de promotion invalide.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title          = trim($_POST['title']);
    $description    = trim($_POST['description']);
    $highlight_text = trim($_POST['highlight_text']);

    try {
        // Update only the title, description, and highlight_text
        $stmt = $pdo->prepare("
            UPDATE promotions
            SET title = ?, description = ?, highlight_text = ?
            WHERE id = ?
        ");
        $stmt->execute([$title, $description, $highlight_text, $promotion_id]);
        echo '<script>alert("Promotion mise à jour avec succès.");</script>';
        echo '<script>window.location.href = "promotions.php";</script>';
        exit;
    } catch (Exception $e) {
        echo '<div class="alert alert-danger">Erreur lors de la mise à jour de la promotion : ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
?>

<?php include_once 'includes/header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Modifier une Promotion</h2>

    <!-- Form to Edit Promotion -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Modifier la Promotion</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="day_name" class="form-label">Jour</label>
                    <input type="text" class="form-control" id="day_name" value="<?php echo htmlspecialchars($promotion['day_name']); ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label">Titre</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($promotion['title']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($promotion['description']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="highlight_text" class="form-label">Texte en surbrillance (optionnel)</label>
                    <input type="text" class="form-control" id="highlight_text" name="highlight_text" value="<?php echo htmlspecialchars($promotion['highlight_text']); ?>">
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="promotions.php" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>