<?php
    $current_page = basename($_SERVER['PHP_SELF']);

    $page_title = '';
    switch ($current_page) {
        case 'index.php':
            $page_title = 'Accueil';
            break;
        case 'menu.php':
            $page_title = 'Menu';
            break;
        case 'about.php':
            $page_title = 'A Propos';
            break;
        case 'book.php':
            $page_title = 'Réserver';
            break;
        case 'order_status.php':
            $page_title = 'Status De Commande';
            break;
        case 'cart.php':
            $page_title = 'Cart';
            break;
        case 'checkout.php':
            $page_title = 'Caisse';
            break;
        default:
            $page_title = '';
    }
    // Calculate cart count for the icon
    $cart_count = 0;
    if (isset($_COOKIE['cart'])) {
        $cart_data = json_decode($_COOKIE['cart'], true);
        if (! empty($cart_data)) {
            $cart_count = array_sum($cart_data);
        }
    }
?>

<title><?php echo $page_title; ?><?php echo $page_title !== '' ? ' - ' : ''; ?>ELBARAKA</title>

<nav class="navbar navbar-expand-lg custom_nav-container">
    <a class="navbar-brand" href="index.php">
        <span>ELBARAKA</span>
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mx-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Accueil</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="menu.php">Menu</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="about.php">À propos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="book.php">Réserver</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="order_status.php">Statut de Commande</a>
            </li>
        </ul>
        <div class="user_option">
            <a href="cart.php" class="cart_link">
                <i class="fa fa-shopping-cart"></i>
                <?php if ($cart_count >= 0): ?>
                <span class="cart-count"><?php echo $cart_count; ?></span>
                <?php endif; ?>
            </a>

        </div>
    </div>
</nav>
