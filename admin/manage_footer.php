<?php
    session_start();

    // Redirect to login page if user is not logged in
    if (! isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    require_once 'db.php'; // Include database connection

    // Fetch existing footer settings
    try {
        $stmt            = $pdo->query("SELECT * FROM footer ORDER BY id");
        $footer = $stmt->fetch(PDO::FETCH_ASSOC);

        // If no settings exist, initialize empty values
        if (! $footer) {
            $footer = [
                'address'    => '',
                'phone'      => '',
                'email'      => '',
                'hours_days' => '',
                'hours_time' => '',
                'facebook'   => '',
                'instagram'  => '',
                'twitter'    => '',
            ];
        }
    } catch (PDOException $e) {
        die("Erreur lors de la récupération des paramètres du pied de page : " . $e->getMessage());
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $address    = trim($_POST['address']);
        $phone      = trim($_POST['phone']);
        $email      = trim($_POST['email']);
        $hours_days = trim($_POST['hours_days']);
        $hours_time = trim($_POST['hours_time']);
        $facebook   = trim($_POST['facebook']);
        $instagram  = trim($_POST['instagram']);
        $twitter    = trim($_POST['twitter']);

        try {
            // Check if settings already exist
            $stmt = $pdo->prepare("SELECT id FROM footer");
            $stmt->execute();
            $existing_settings = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existing_settings) {
                // Update existing settings
                $stmt = $pdo->prepare("
                UPDATE footer
                SET address = ?, phone = ?, email = ?, hours_days = ?, hours_time = ?, facebook = ?, instagram = ?, twitter = ?
                WHERE id = ?
            ");
                $stmt->execute([$address, $phone, $email, $hours_days, $hours_time, $facebook, $instagram, $twitter, $existing_settings['id']]);
            } else {
                // Insert new settings
                $stmt = $pdo->prepare("
                INSERT INTO footer (address, phone, email, hours_days, hours_time, facebook, instagram, twitter)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
                $stmt->execute([$address, $phone, $email, $hours_days, $hours_time, $facebook, $instagram, $twitter]);
            }

            echo '<script>alert("Paramètres mis à jour avec succès.");</script>';
            echo '<script>window.location.href = "manage_footer.php";</script>';

        } catch (Exception $e) {
            echo '<div class="alert alert-danger">Erreur lors de la mise à jour des paramètres : ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    }
?>

<?php include_once 'includes/header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Gestion du Pied de Page</h2>

    <!-- Form to Edit Footer Settings -->
    <form method="POST" action="">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="address" class="form-label">Adresse</label>
                    <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($footer['address']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Téléphone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($footer['phone']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($footer['email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="hours_days" class="form-label">Jours d'ouverture</label>
                    <input type="text" class="form-control" id="hours_days" name="hours_days" value="<?php echo htmlspecialchars($footer['hours_days']); ?>" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="hours_time" class="form-label">Horaires d'ouverture</label>
                    <input type="text" class="form-control" id="hours_time" name="hours_time" value="<?php echo htmlspecialchars($footer['hours_time']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="facebook" class="form-label">Facebook URL</label>
                    <input type="url" class="form-control" id="facebook" name="facebook" value="<?php echo htmlspecialchars($footer['facebook']); ?>">
                </div>
                <div class="mb-3">
                    <label for="instagram" class="form-label">Instagram URL</label>
                    <input type="url" class="form-control" id="instagram" name="instagram" value="<?php echo htmlspecialchars($footer['instagram']); ?>">
                </div>
                <div class="mb-3">
                    <label for="twitter" class="form-label">Twitter URL</label>
                    <input type="url" class="form-control" id="twitter" name="twitter" value="<?php echo htmlspecialchars($footer['twitter']); ?>">
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>

<?php include_once 'includes/footer.php'; ?>