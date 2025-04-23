<?php
    session_start();

    // Redirect to login page if user is not logged in
    if (! isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    require_once 'db.php'; // Include database connection

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $heading     = trim($_POST['heading']);
        $description = trim($_POST['description']);
        $image_url   = '';

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $targetDir  = '../images/about/';
            $fileName   = preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($_FILES['image']['name']));
            $targetFile = $targetDir . $fileName;

            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($_FILES['image']['type'], $allowedTypes)) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $image_url = '../images/about/' . $fileName; // Update image URL
                } else {
                    echo '<script>alert("Erreur lors du téléchargement de l\'image.");</script>';
                }
            } else {
                echo '<script>alert("Type d\'image non autorisé. Formats acceptés : JPG, PNG, GIF.");</script>';
            }
        }

        try {
            // Insert the new about record into the database
            $stmt = $pdo->prepare("
            INSERT INTO about (heading, description, image_url)
            VALUES (?, ?, ?)
        ");
            $stmt->execute([$heading, $description, $image_url]);

            echo '<script>alert("Informations ajoutées avec succès.");</script>';
            echo '<script>window.location.href = "about.php";</script>';
        } catch (Exception $e) {
            echo '<script>alert("Erreur lors de l\'ajout des informations : ' . htmlspecialchars($e->getMessage()) . '");</script>';
            echo '<script>window.location.href = "add_about.php";</script>';
        }
    }
?>

<?php include_once 'includes/header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Ajouter des Informations À Propos</h2>

    <form method="POST" action="" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="heading" class="form-label">Titre</label>
            <input type="text" class="form-control" id="heading" name="heading" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Image (Facultatif)</label>
            <input type="file" class="form-control" id="image" name="image">
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="dashboard.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<?php include_once 'includes/footer.php'; ?>