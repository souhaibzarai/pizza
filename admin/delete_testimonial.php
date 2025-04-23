<?php
session_start();

if (! isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'db.php';

$testimonial_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

try {

    $stmt = $pdo->prepare("SELECT image_url FROM testimonials WHERE id = ?");
    $stmt->execute([$testimonial_id]);
    $testimonial = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("DELETE FROM testimonials WHERE id = ?");
    $stmt->execute([$testimonial_id]);

    if ($testimonial['image_url']) {
        $imagePath = realpath('../' . $testimonial['image_url']);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    echo '<script>alert("Témoignage supprimé avec succès.");</script>';
    echo '<script>window.location.href = "testimonials.php";</script>';

} catch (Exception $e) {
    echo '<script>alert("Erreur lors de la suppression du témoignage : ' . htmlspecialchars($e->getMessage()) . '");</script>';
    echo '<script>window.location.href = "testimonials.php";</script>';
}
