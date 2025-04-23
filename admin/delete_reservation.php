<?php
session_start();
if (! isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: reservations.php?status=deleted');
        exit;
    } catch (PDOException $e) {
        error_log("Error deleting reservation: " . $e->getMessage());
        header('Location: reservations.php?status=error');
        exit;
    }
} else {
    header('Location: reservations.php');
    exit;
}
