<?php
    session_start();

    
    if (! isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    require_once 'db.php'; 

    
    try {
        $stmt       = $pdo->query("SELECT * FROM about");
        $about_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (! $about_data) {
            
            header('Location: add_about.php');
            exit;
        }
    } catch (PDOException $e) {
        die("Erreur lors de la récupération des données : " . $e->getMessage());
    }

    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $heading     = trim($_POST['heading']);
        $description = trim($_POST['description']);
        $image_url   = $about_data['image_url']; 

        

        try {
            
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $targetDir  = '../images/about/';
                $fileName   = preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($_FILES['image']['name']));
                $targetFile = $targetDir . $fileName;

                
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (in_array($_FILES['image']['type'], $allowedTypes)) {
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                        
                        if ($about_data['image_url'] && file_exists('../' . $about_data['image_url'])) {
                            unlink('../' . $about_data['image_url']);
                        }
                        $image_url = '../images/about/' . $fileName; 
                    } else {
                        echo '<script>alert("Erreur lors du téléchargement de l\'image.");</script>';
                    }
                } else {
                    echo '<script>alert("Type d\'image non autorisé. Formats acceptés : JPG, PNG, GIF.");</script>';
                }

                $stmt = $pdo->prepare("UPDATE about SET heading = ?, description = ?, image_url = ? WHERE id = ?");
                $stmt->execute([$heading, $description, $image_url, $about_data['id']]);
            }
            else {
                $stmt = $pdo->prepare("UPDATE about SET heading = ?, description = ? WHERE id = ?");
                $stmt->execute([$heading, $description, $about_data['id']]);
            }

            echo '<script>alert("Informations mises à jour avec succès.");</script>';
            echo '<script>window.location.href = "about.php";</script>';
        } catch (Exception $e) {
            echo '<script>alert("Erreur lors de la mise à jour des informations : ' . htmlspecialchars($e->getMessage()) . '");</script>';
            echo '<script>window.location.href = "about.php";</script>';
        }
    }
?>

<?php include_once 'includes/header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Gestion de la Page À Propos</h2>

    <form method="POST" action="" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="heading" class="form-label">Titre</label>
            <input type="text" class="form-control" id="heading" name="heading" value="<?php echo htmlspecialchars($about_data['heading']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="5" required><?php echo htmlspecialchars($about_data['description']); ?></textarea>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Image (Facultatif)</label>
            <input type="file" class="form-control" id="image" name="image">
            <?php if (! empty($about_data['image_url'])): ?>
                <div class="mt-2">
                    <strong>Image actuelle :</strong><br>
                    <img src="<?php echo htmlspecialchars($about_data['image_url']); ?>" alt="Image actuelle" style="max-width: 200px; margin-top: 10px;">
                </div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="dashboard.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<?php include_once 'includes/footer.php'; ?>