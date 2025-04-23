<?php
    session_start();

    if (! isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    require_once 'db.php';
    $message = '';
    $error   = '';

    // Get user data
    try {
        $stmt = $pdo->prepare("SELECT username, full_name, type FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (! $user) {
            $error = 'Utilisateur non trouvé.';
            exit;
        }
    } catch (Exception $e) {
        $error = 'Erreur lors du chargement du profil : ' . htmlspecialchars($e->getMessage());
        exit;
    }

    // Handle profile update
    if (isset($_POST['update_profile'])) {
        $full_name = trim($_POST['full_name']);

        if (empty($full_name)) {
            $error = "Le nom ne peut pas être vide";
        } else {
            try {
                $stmt = $pdo->prepare("UPDATE users SET full_name = ? WHERE id = ?");
                if ($stmt->execute([$full_name, $_SESSION['user_id']])) {
                    $message           = "Profil mis à jour avec succès !";
                    $user['full_name'] = $full_name;
                } else {
                    $error = "Erreur lors de la mise à jour du profil";
                }
            } catch (Exception $e) {
                $error = "Erreur: " . htmlspecialchars($e->getMessage());
            }
        }
    }

    // Handle password change
    if (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password     = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $error = "Tous les champs de mot de passe sont requis";
        } elseif ($new_password !== $confirm_password) {
            $error = "Les nouveaux mots de passe ne correspondent pas";
        } elseif (strlen($new_password) < 6) {
            $error = "Le mot de passe doit contenir au moins 6 caractères";
        } else {
            try {
                // Verify current password
                $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

                if (password_verify($current_password, $user_data['password'])) {
                    // Update password
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt            = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");

                    if ($stmt->execute([$hashed_password, $_SESSION['user_id']])) {
                        $message = "Mot de passe changé avec succès !";
                    } else {
                        $error = "Erreur lors du changement de mot de passe";
                    }
                } else {
                    $error = "Le mot de passe actuel est incorrect";
                }
            } catch (Exception $e) {
                $error = "Erreur: " . htmlspecialchars($e->getMessage());
            }
        }
    }

    $page_title = 'Mon Profil';
    include_once 'includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-12">
            <?php if ($message): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card mb-4 shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>Informations du compte</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Nom d'utilisateur</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0"><?php echo htmlspecialchars($user['username']); ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Nom complet</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0"><?php echo htmlspecialchars($user['full_name']); ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Type d'utilisateur</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0"><?php echo ucfirst(htmlspecialchars($user['type'])); ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Dernière connexion</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0"><?php echo date('d/m/Y H:i'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4 mb-lg-0 shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Sécurité du compte</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush rounded-3">
                        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <div>
                                <i class="fas fa-lock text-warning fa-lg me-3"></i>
                                <span>Mot de passe</span>
                            </div>
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                Modifier
                            </button>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <div>
                                <i class="fas fa-user-edit text-primary fa-lg me-3"></i>
                                <span>Informations personnelles</span>
                            </div>
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                Modifier
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editProfileModalLabel"><i class="fas fa-user-edit me-2"></i>Modifier le profil</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="username" class="form-label">Nom d'utilisateur</label>
                        <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                        <div class="form-text">Le nom d'utilisateur ne peut pas être modifié.</div>
                    </div>
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Nom complet</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" name="update_profile" class="btn btn-primary"><i class="fas fa-save me-1"></i>Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="changePasswordModalLabel"><i class="fas fa-key me-2"></i>Changer le mot de passe</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Mot de passe actuel</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Nouveau mot de passe</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="form-text">Le mot de passe doit contenir au moins 6 caractères.</div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" name="change_password" class="btn btn-danger"><i class="fas fa-save me-1"></i>Changer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript for password visibility toggle -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleButtons = document.querySelectorAll('.toggle-password');

    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Auto-close alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const closeButton = alert.querySelector('.btn-close');
            if(closeButton) {
                closeButton.click();
            }
        }, 5000);
    });
});
</script>

<?php include_once 'includes/footer.php'; ?>