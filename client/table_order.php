<?php
    // Include the database connection file
    require_once 'db.php';

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve and sanitize form data
        $name             = trim($_POST['name']);
        $phone            = trim($_POST['phone']);
        $email            = trim($_POST['email']);
        $guests           = (int) $_POST['guests'];
        $reservation_date = $_POST['date'];

        // Validate inputs
        if (empty($name) || empty($phone) || empty($email) || empty($guests) || empty($reservation_date)) {
            echo '<p class="error">Veuillez remplir tous les champs.</p>';
        } elseif (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo '<p class="error">Adresse e-mail invalide.</p>';
        } else {
            try {
                // Insert reservation into the database
                $stmt = $pdo->prepare("
                INSERT INTO reservations (name, phone, email, guests, reservation_date)
                VALUES (:name, :phone, :email, :guests, :reservation_date)
            ");
                $stmt->execute([
                    ':name'             => $name,
                    ':phone'            => $phone,
                    ':email'            => $email,
                    ':guests'           => $guests,
                    ':reservation_date' => $reservation_date,
                ]);

                echo '<p class="success">Votre réservation a été enregistrée avec succès!</p>';
            } catch (PDOException $e) {
                error_log("Error inserting reservation: " . $e->getMessage());
                echo '<p class="error">Une erreur est survenue lors de l\'enregistrement de votre réservation.</p>';
            }
        }
    }
?>

<div class="col-md-6">
    <div class="form_container">
        <form method="POST" action="">
            <div>
                <input type="text" class="form-control" name="name" placeholder="Votre nom" required />
            </div>
            <div>
                <input type="tel" class="form-control" name="phone" placeholder="Numéro de téléphone" required />
            </div>
            <div>
                <input type="email" class="form-control" name="email" placeholder="Votre e-mail" required />
            </div>
            <div>
                <select class="form-control nice-select wide" name="guests" required>
                    <option value="" disabled selected>Combien de personnes ?</option>
                    <?php for ($i = 1; $i <= 30; $i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div>
                <input type="date" class="form-control" name="date" required />
            </div>
            <div class="btn_box">
                <button type="submit" class="btn btn-primary">Réserver maintenant</button>
            </div>
        </form>
    </div>
</div>