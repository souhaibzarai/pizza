<?php
    session_start();

    if (! isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
        header('Location: login.php');
        exit;
    }

    require_once 'db.php';

    $user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (! $user) {
            die('<div class="alert alert-danger">Utilisateur introuvable.</div>');
        }
    } catch (PDOException $e) {
        die('<div class="alert alert-danger">Erreur lors de la récupération de l\'utilisateur : ' . $e->getMessage() . '</div>');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $full_name = trim($_POST['full_name']);
        $type      = trim($_POST['type']);

        try {

            if (empty($full_name)) {
                echo '<div class="alert alert-danger">Le nom complet est requis.</div>';
            } elseif (! in_array($type, ['admin', 'limited'])) {
                echo '<div class="alert alert-danger">Le type d\'utilisateur n\'est pas valide.</div>';
            } else {

                $stmt = $pdo->prepare("
                UPDATE users
                SET full_name = ?, type = ?
                WHERE id = ?
            ");
                $stmt->execute([$full_name, $type, $user_id]);

                echo '<script>alert("Utilisateur mis à jour avec succès.");</script>';
                echo '<script>window.location.href = "manage_users.php";</script>';
            }
        } catch (Exception $e) {
            echo '<script>alert("Erreur lors de la mise à jour de l\'utilisateur : ' . htmlspecialchars($e->getMessage()) . '");</script>';
        }
    }
?>

<?php include_once 'includes/header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Modifier un Utilisateur</h2>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulaire de Modification d'Utilisateur</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="username" class="form-label">Nom d'utilisateur</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="full_name" class="form-label">Nom Complet *</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Type *</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="admin"                                                                                                                                                                                     <?php echo($user['type'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                        <option value="limited"                                                                                                                                                                                             <?php echo($user['type'] === 'limited') ? 'selected' : ''; ?>>Limited</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="manage_users.php" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>