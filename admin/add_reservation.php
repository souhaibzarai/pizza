<?php
session_start();
if (! isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name             = trim($_POST['name']);
    $phone            = trim($_POST['phone']);
    $email            = trim($_POST['email']);
    $guests           = (int) $_POST['guests'];
    $reservation_date = $_POST['reservation_date'];
    $status           = $_POST['status'];

    try {
        $stmt = $pdo->prepare("INSERT INTO reservations (name, phone, email, guests, reservation_date, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $phone, $email, $guests, $reservation_date, $status]);
        header('Location: reservations.php?status=added');
        exit;
    } catch (PDOException $e) {
        error_log("Error inserting reservation: " . $e->getMessage());
        header('Location: reservations.php?status=error');
        exit;
    }
}
