<?php
setcookie('cart', '', time() - 3600); // Expire the cookie immediately
header('Location: index.php'); // Redirect to cart page
exit;
?>