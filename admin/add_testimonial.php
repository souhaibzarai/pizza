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
        $name        = trim($_POST['name']);
        $testimonial = trim($_POST['testimonial']);
        $image_url   = '';

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $targetDir  = '../images/client_avis/';
            $fileName   = basename($_FILES['image']['name']);
            $targetFile = $targetDir . $fileName;

            // Move uploaded file to the target directory
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $image_url = $targetFile;
            } else {
                echo '<script>alert("Erreur lors du téléchargement de l\'image.");</script>';
                echo '<script>window.location.href = "testimonials.php";</script>';

            }
        }

        try {
            // Insert testimonial into the database
            $stmt = $pdo->prepare("
            INSERT INTO testimonials (name, testimonial, image_url)
            VALUES (?, ?, ?)
        ");
            $stmt->execute([$name, $testimonial, $image_url]);

            echo '<script>alert("Témoignage ajouté avec succès.");</script>';
            echo '<script>window.location.href = "testimonials.php";</script>';

        } catch (Exception $e) {
            echo '<script>alert("Erreur lors de l\'ajout du témoignage : ' . htmlspecialchars($e->getMessage()) . '");</script>';
            echo '<script>window.location.href = "testimonials.php";</script>';

        }
    }
?>

<?php include_once 'includes/header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Ajouter un Témoignage</h2>

    <form method="POST" action="" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Nom</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="testimonial" class="form-label">Témoignage</label>
            <textarea class="form-control" id="testimonial" name="testimonial" rows="5" required></textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image (Facultatif)</label>
            <input type="file" class="form-control" id="image" name="image">
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>

<?php include_once 'includes/footer.php'; ?>