<?php
    session_start();

    // Redirect to login page if user is not logged in
    if (! isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    require_once 'db.php'; // Include database connection

    // Handle form submission (Add/Update/Delete)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';

        if ($action === 'add') {
            // Add a new promotion
            $day_name       = trim($_POST['day_name']);
            $day_of_week    = intval($_POST['day_of_week']);
            $title          = trim($_POST['title']);
            $description    = trim($_POST['description']);
            $highlight_text = trim($_POST['highlight_text']);

            try {
                // Check if a promotion for this day_of_week already exists
                $stmt = $pdo->prepare("SELECT id FROM promotions WHERE day_of_week = ?");
                $stmt->execute([$day_of_week]);
                $existing_promotion = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($existing_promotion) {
                    // Delete the existing promotion for this day_of_week
                    $stmt = $pdo->prepare("DELETE FROM promotions WHERE day_of_week = ?");
                    $stmt->execute([$day_of_week]);
                }

                // Insert the new promotion
                $stmt = $pdo->prepare("
                INSERT INTO promotions (day_name, day_of_week, title, description, highlight_text)
                VALUES (?, ?, ?, ?, ?)
            ");
                $stmt->execute([$day_name, $day_of_week, $title, $description, $highlight_text]);

                echo '<script>alert("Promotion ajoutée avec succès.");</script>';

            } catch (Exception $e) {
                echo '<div class="alert alert-danger">Erreur lors de l\'ajout de la promotion : ' . htmlspecialchars($e->getMessage()) . '</div>';
            }

        } elseif ($action === 'delete') {
            // Delete a promotion by ID
            $id = intval($_POST['id']);

            try {
                $stmt = $pdo->prepare("DELETE FROM promotions WHERE id = ?");
                $stmt->execute([$id]);

                // Optional: redirect to avoid resubmission on refresh
                header("Location: promotions.php");
                exit;

            } catch (Exception $e) {
                echo '<div class="alert alert-danger">Erreur lors de la suppression de la promotion : ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        }
    }

    // Fetch all promotions from the database
    try {
        $stmt       = $pdo->query("SELECT * FROM promotions ORDER BY day_of_week");
        $promotions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erreur lors de la récupération des promotions : " . $e->getMessage());
    }
?>

<?php include_once 'includes/header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Gestion des Promotions</h2>

    <!-- Form to Add/Edit Promotions -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Ajouter/Modifier une Promotion</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="id" value="">

                <div class="mb-3">
                    <label for="day_name" class="form-label">Jour</label>
                    <select class="form-select" id="day_name" name="day_name" required>
                        <option value="Dimanche" data-day="0">Dimanche</option>
                        <option value="Lundi" data-day="1">Lundi</option>
                        <option value="Mardi" data-day="2">Mardi</option>
                        <option value="Mercredi" data-day="3">Mercredi</option>
                        <option value="Jeudi" data-day="4">Jeudi</option>
                        <option value="Vendredi" data-day="5">Vendredi</option>
                        <option value="Samedi" data-day="6">Samedi</option>
                    </select>
                    <input type="hidden" id="day_of_week" name="day_of_week">
                </div>

                <div class="mb-3">
                    <label for="title" class="form-label">Titre</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="highlight_text" class="form-label">Texte en surbrillance (optionnel)</label>
                    <input type="text" class="form-control" id="highlight_text" name="highlight_text">
                </div>

                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
    </div>

    <!-- List of Promotions -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Promotions</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Jour</th>
                            <th>Titre</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (! empty($promotions)): ?>
<?php foreach ($promotions as $promotion): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($promotion['day_name']); ?></td>
                                    <td><?php echo htmlspecialchars($promotion['title']); ?></td>
                                    <td>
                                        <?php
                                            $description = htmlspecialchars($promotion['description']);
                                            if (! empty($promotion['highlight_text'])) {
                                                $highlight   = '<span class="highlight">' . htmlspecialchars($promotion['highlight_text']) . '</span>';
                                                $description = str_replace('{highlight}', $highlight, $description);
                                            }
                                            echo $description;
                                        ?>
                                    </td>
                                    <td>
                                        <a href="edit_promotion.php?id=<?php echo htmlspecialchars($promotion['id']); ?>" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <form method="POST" action="" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette promotion ?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($promotion['id']); ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
<?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">Aucune promotion disponible</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // Populate day_of_week based on selected day_name
    document.getElementById('day_name').addEventListener('change', function () {
        const dayOfWeek = this.options[this.selectedIndex].getAttribute('data-day');
        document.getElementById('day_of_week').value = dayOfWeek;
    });

    // Trigger default selection (in case page reloads without selection)
    document.getElementById('day_name').dispatchEvent(new Event('change'));
</script>

<?php include_once 'includes/footer.php'; ?>
