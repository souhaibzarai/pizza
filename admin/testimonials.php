<?php
    session_start();

    // Redirect to login page if user is not logged in
    if (! isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    require_once 'db.php'; // Include database connection

    // Fetch all testimonials
    try {
        $stmt         = $pdo->query("SELECT * FROM testimonials ORDER BY id DESC");
        $testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erreur lors de la récupération des témoignages : " . $e->getMessage());
    }
?>

<?php include_once 'includes/header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Gestion des Témoignages</h2>

    <!-- Add Testimonial Button -->
    <a href="add_testimonial.php" class="btn btn-primary mb-3">Ajouter un Témoignage</a>

    <!-- List of Testimonials -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Témoignages</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Témoignage</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (! empty($testimonials)): ?>
<?php foreach ($testimonials as $testimonial): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($testimonial['name']); ?></td>
                                    <td><?php echo htmlspecialchars(substr($testimonial['testimonial'], 0, 100)); ?>...</td>
                                    <td>
                                        <?php if ($testimonial['image_url']): ?>
                                            <img src="<?php echo htmlspecialchars($testimonial['image_url']); ?>" alt="Image" style="max-width: 100px;">
                                        <?php else: ?>
                                            Aucune image
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="edit_testimonial.php?id=<?php echo htmlspecialchars($testimonial['id']); ?>" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <a href="delete_testimonial.php?id=<?php echo htmlspecialchars($testimonial['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce témoignage ?');">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
<?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">Aucun témoignage disponible</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>