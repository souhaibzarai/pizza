<?php
    session_start();

    if (! isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
        header('Location: login.php');
        exit;
    }

    require_once 'db.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username  = trim($_POST['username']);
        $password  = $_POST['password'];
        $type      = trim($_POST['type']);
        $full_name = trim($_POST['full_name']);

        try {
            if (empty($username) || empty($password) || empty($full_name)) {
                echo '<div class="alert alert-danger">Tous les champs sont requis.</div>';
            } elseif (! in_array($type, ['admin', 'limited'])) {
                echo '<div class="alert alert-danger">Le type d\'utilisateur n\'est pas valide.</div>';
            } else {

                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("
                INSERT INTO users (username, password, type, full_name)
                VALUES (?, ?, ?, ?)
            ");
                $stmt->execute([$username, $hashed_password, $type, $full_name]);

                echo '<script>alert("Utilisateur ajouté avec succès.");</script>';
                echo '<script>window.location.href = "manage_users.php";</script>';
            }
        } catch (Exception $e) {
            echo '<div class="alert alert-danger">Erreur lors de l\'ajout de l\'utilisateur : ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    }
?>

<?php include_once 'includes/header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Ajouter un Utilisateur</h2>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulaire d'Ajout d'Utilisateur</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="username" class="form-label">Nom d'utilisateur *</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe *</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Type *</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="admin">Admin</option>
                        <option value="limited">Limited</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="full_name" class="form-label">Nom Complet *</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" required>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="manage_users.php" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>