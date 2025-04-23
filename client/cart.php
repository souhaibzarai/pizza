<?php include_once 'header.php'; ?>

<body class="sub_page">
    <div class="hero_area">
        <div class="bg-box" style="background: linear-gradient(135deg, #030303, #696868)">
        </div>

        <!-- Header Section -->
        <header class="header_section">
            <div class="container">
                <?php include_once 'nav.php'; ?>
            </div>
        </header>
    </div>

    <!-- Cart Section -->
    <section class="cart_section layout_padding">
        <div class="container">
            <div class="heading_container heading_center">
                <h2>Votre Panier</h2>
            </div>

            <?php
                // Get cart data from cookie
                $cart_data  = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];
                $cart_total = 0;

                // If cart has items, fetch product details from database
                $cart_items = [];
                if (! empty($cart_data)) {
                    require_once 'db.php';

                    // Prepare placeholders for SQL IN clause
                    $placeholders = implode(',', array_fill(0, count(array_keys($cart_data)), '?'));

                    if (! empty($placeholders)) {
                        $stmt = $pdo->prepare("
                            SELECT id, name, description, price, discount, image_url
                            FROM menu_items
                            WHERE id IN ($placeholders)
                        ");
                        $stmt->execute(array_keys($cart_data));
                        $menu_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        // Build cart items with full details
                        foreach ($menu_items as $item) {
                            $quantity   = $cart_data[$item['id']];
                            $discounted_price = $item['price'];
                            if ($item['discount'] > 0) {
                                $discounted_price = $item['price'] * (1 - $item['discount'] / 100);
                            }
                            $item_total = $discounted_price * $quantity;
                            $cart_total += $item_total;

                            $cart_items[] = [
                                'id'          => $item['id'],
                                'name'        => $item['name'],
                                'description' => $item['description'],
                                'price'       => $discounted_price,
                                'image_url'   => $item['image_url'],
                                'quantity'    => $quantity,
                                'item_total'  => $item_total,
                            ];
                        }
                    }
                }
            ?>

            <div class="cart-content">
                <?php if (empty($cart_items)): ?>
                    <div class="empty-cart">
                        <div class="empty-cart-icon">
                            <img src="icons/empty-cart.png" alt="Empty Cart">
                        </div>
                        <h4>Votre panier est vide</h4>
                        <p>Explorez notre menu et découvrez nos délicieuses spécialités</p>
                        <a href="menu.php" class="btn btn-order">Voir le Menu</a>
                    </div>
                <?php else: ?>
                    <div class="row cart-container">
                        <div class="col-lg-8">
                            <div class="cart-items">
                                <?php foreach ($cart_items as $item): ?>
                                    <div class="cart-item">
                                        <div class="item-image">
                                            <img src="<?php echo htmlspecialchars($item['image_url']) ?>" alt="<?php echo htmlspecialchars($item['name']) ?>">
                                        </div>
                                        <div class="item-details">
                                            <h5><?php echo htmlspecialchars($item['name']) ?></h5>
                                            <p class="item-description"><?php echo htmlspecialchars($item['description']) ?></p>
                                            <div class="price"><?php echo number_format($item['price'], 2) ?> DH</div>
                                        </div>
                                        <div class="item-quantity">
                                            <div class="quantity-controls">
                                                <button class="quantity-btn decrease" onclick="updateQuantity(<?php echo $item['id'] ?>,<?php echo $item['quantity'] - 1 ?>)">-</button>
                                                <span class="quantity"><?php echo $item['quantity'] ?></span>
                                                <button class="quantity-btn increase" onclick="updateQuantity(<?php echo $item['id'] ?>,<?php echo $item['quantity'] + 1 ?>)">+</button>
                                            </div>
                                        </div>
                                        <div class="item-total">
                                            <?php echo number_format($item['item_total'], 2) ?> DH
                                        </div>
                                        <div class="item-remove">
                                            <button class="remove-btn" onclick="removeItem(<?php echo $item['id'] ?>)">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="cart-summary">
                                <h4>Résumé de commande</h4>
                                <div class="summary-item">
                                    <span>Sous-total</span>
                                    <span><?php echo number_format($cart_total, 2) ?> DH</span>
                                </div>
                                <div class="summary-item">
                                    <span>Frais de livraison</span>
                                    <span>
                                        <?php
                                            // Change the free delivery threshold here:
                                            $delivery_fee = ($cart_total > 250) ? 0 : 20;
                                            echo ($delivery_fee > 0) ? number_format($delivery_fee, 2) . ' DH' : 'Gratuit';
                                        ?>
                                    </span>
                                </div>
                                <div class="summary-divider"></div>
                                <div class="summary-total">
                                    <span>Total</span>
                                    <span><?php echo number_format($cart_total + $delivery_fee, 2) ?> DH</span>
                                </div>

                                <div class="summary-actions">
                                    <a href="checkout.php" class="btn btn-checkout">Passer à la caisse</a>
                                    <button class="btn btn-clear" onclick="clearCart()">Vider le panier</button>
                                </div>

                                <div class="shipping-note">
                                    <p>Livraison gratuite pour les commandes de plus de 250 DH</p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php include_once 'footer.php'; ?>

    <!-- Scripts -->
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script>
        function updateQuantity(productId, newQuantity) {
            if (newQuantity <= 0) {
                removeItem(productId);
                return;
            }

            fetch('update_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: newQuantity,
                    action: 'update'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Erreur lors de la mise à jour du panier');
                }
            });
        }

        function removeItem(productId) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet article du panier?')) {
                fetch('update_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        action: 'remove'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Erreur lors de la suppression de l\'article');
                    }
                });
            }
        }

        function clearCart() {
            if (confirm('Êtes-vous sûr de vouloir vider votre panier?')) {
                fetch('update_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'clear'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Erreur lors de la suppression du panier');
                    }
                });
            }
        }
    </script>
</body>
</html>
