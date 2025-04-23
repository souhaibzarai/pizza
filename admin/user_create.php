<?php
    session_start();

    // comment mn hna ila bghit n9ad user fash tkun table users khawya
    if (! isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
        header('Location: login.php');
        exit;
    }
    // tal hna

    require_once 'db.php';

    $errors  = [];
    $success = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $full_name        = $_POST['full_name'] ?? '';
        $username         = $_POST['username'] ?? '';
        $password         = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $type             = $_POST['type'] ?? 'admin';

        // Validate full name
        if (empty($full_name)) {
            $errors[] = 'Le nom complet est requis.';
        }

        // Validate username
        if (empty($username)) {
            $errors[] = "Le nom d'utilisateur est requis.";
        } else {
            // Check if username already exists
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($existing_user) {
                $errors[] = "Ce nom d'utilisateur est déjà utilisé.";
            }
        }

        // Validate password
        if (empty($password)) {
            $errors[] = "Le mot de passe est requis.";
        } elseif (strlen($password) < 6) {
            $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
        }

        // Validate confirm password
        if (empty($confirm_password)) {
            $errors[] = "La confirmation du mot de passe est requise.";
        } elseif ($password !== $confirm_password) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }

        // Validate user type
        if (! in_array($type, ['admin', 'limited'])) {
            $errors[] = "Type d'utilisateur invalide.";
        }

        // If no errors, save user to database
        if (empty($errors)) {
            try {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                // Insert user into users table
                $stmt = $pdo->prepare("
                INSERT INTO users (full_name, username, password, type, created_at)
                VALUES (?, ?, ?, ?, NOW())
            ");
                $stmt->execute([$full_name, $username, $hashed_password, $type]);

                $success = true;
            } catch (Exception $e) {
                $errors[] = "Une erreur s'est produite lors de l'enregistrement de votre compte. Veuillez réessayer.";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un utilisateur</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/admin.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include_once 'nav.php'; ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header">
                        <h4 class="text-center">Créer un utilisateur</h4>
                    </div>
                    <div class="card-body">
                        <?php if (! empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
<?php if ($success): ?>
                            <div class="alert alert-success">
                                <p>Utilisateur créé avec succès.</p>
                            </div>
                        <?php endif; ?>
                        <form method="POST" action="" id="create-user-form">
                            <div class="form-group mb-3">
                                <label for="full_name">Nom Complet *</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="username">Nom d'utilisateur *</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="password">Mot de passe *</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="confirm_password">Confirmez le mot de passe *</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="type">Type d'utilisateur</label>
                                <select class="form-select" id="type" name="type">
                                    <option value="admin">Admin</option>
                                    <option value="limited">Limité</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success btn-block w-100">Créer l'utilisateur</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap 5 JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js"></script>
</body>
</html>