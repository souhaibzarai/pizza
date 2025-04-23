<?php
session_start();
// Ensure the user is logged in and is an admin
if (! isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once 'db.php'; // Include the database connection

// Fetch the menu item to delete
if (! isset($_GET['id'])) {
    header('Location: menu_items.php');
    exit;
}
$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT image_url FROM menu_items WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if ($item) {
    // Delete the image from the server if it exists
    if (! empty($item['image_url']) && file_exists($item['image_url'])) {
        unlink($item['image_url']);
    }

    // Delete the menu item from the database
    $stmt = $pdo->prepare("DELETE FROM menu_items WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: menu_items.php?status=deleted');
exit;
