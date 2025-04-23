<?php include_once 'header.php'; ?>

<body class="sub_page">
    <div class="hero_area">
       <div class="bg-box" style="background: linear-gradient(135deg, #030303, #696868)">
        </div>w
        <!-- Header Section -->
        <header class="header_section">
            <div class="container">
                <?php include_once 'nav.php'; ?>
            </div>
        </header>
    </div>
    <!-- Confirmation Section -->
    <section class="confirmation_section layout_padding">
        <div class="container">
            <?php
                // Check if order_id is provided
                if (! isset($_GET['order_id']) || empty($_GET['order_id'])) {
                    header('Location: index.php');
                    exit;
                }
                $order_id = $_GET['order_id'];
                // Get order details from database
                require_once 'db.php';
                $stmt = $pdo->prepare("
                    SELECT o.*, COUNT(oi.id) as item_count
                    FROM orders o
                    LEFT JOIN order_items oi ON o.id = oi.order_id
                    WHERE o.order_id = ?
                    GROUP BY o.id
                ");
                $stmt->execute([$order_id]);
                $order = $stmt->fetch(PDO::FETCH_ASSOC);
                if (! $order) {
                    echo '<div class="text-center">';
                    echo '<h2>Commande introuvable</h2>';
                    echo '<p>Nous n\'avons pas pu trouver les détails de votre commande.</p>';
                    echo '<a href="menu.php" class="btn btn-primary mt-3">Retour au Menu</a>';
                    echo '</div>';
                    exit;
                }
            ?>
            <div class="confirmation-container">
                <div class="confirmation-header">
                    <div class="check-icon">
                        <img src="icons/check.png" alt="Success">
                    </div>
                    <h2>Commande confirmée!</h2>
                    <p>Merci pour votre commande. Nous vous appellerons bientôt pour confirmer.</p>
                </div>
                <div class="order-info">
                    <div class="order-detail">
                        <span>Numéro de commande:</span>
                        <strong class="order-id-container">
                            <span id="order-id"><?php echo htmlspecialchars($order['order_id']); ?></span>
                            <button class="btn btn-copy" onclick="copyOrderId()">
                                <img src="icons/copy.png" alt="Copy" width="20px">
                            </button>
                        </strong>
                    </div>
                    <div class="order-detail">
                        <span>Date:</span>
                        <strong><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></strong>
                    </div>
                    <div class="order-detail">
                        <span>Total:</span>
                        <strong><?php echo number_format($order['total'], 2); ?> DH</strong>
                    </div>
                    <div class="order-detail">
                        <span>Mode de paiement:</span>
                        <strong>Paiement à la livraison</strong>
                    </div>
                </div>
                <div class="customer-info">
                            <h4>Informations de livraison</h4>
                            <div class="info-item">
                                <div class="info-label">Nom:</div>
                                <div class="info-value"><?php echo htmlspecialchars($order['full_name']); ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Téléphone:</div>
                                <div class="info-value">
                                    <?php
                                        $phone = htmlspecialchars($order['phone']);
                                        if (strlen($phone) > 4) {
                                            $masked_phone = substr($phone, 0, -4) . str_repeat('*', 4);
                                            echo $masked_phone;
                                        } else {
                                            echo $phone;
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Email:</div>
                                <div class="info-value">
                                    <?php
                                        $email       = htmlspecialchars($order['email']);
                                        $email_parts = explode('@', $email);
                                        if (count($email_parts) == 2) {
                                            $local_part = $email_parts[0];
                                            $domain     = $email_parts[1];
                                            if (strlen($local_part) > 2) {
                                                $masked_local_part = substr($local_part, 0, 2) . str_repeat('*', strlen($local_part) - 2);
                                                echo $masked_local_part . '@' . $domain;
                                            } else {
                                                echo $email;
                                            }
                                        } else {
                                            echo $email;
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Adresse:</div>
                                <div class="info-value">
                                    <?php
                                        $address = htmlspecialchars($order['address']);
                                        if (strlen($address) > 8) {
                                            echo substr($address, 0, 8) . '...';
                                        } else {
                                            echo $address;
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                <div class="order-summary">
                    <h4>Résumé de commande</h4>
                    <div class="order-items">
                        <?php
                            $stmt = $pdo->prepare("
                            SELECT * FROM order_items WHERE order_id = ?
                        ");
                            $stmt->execute([$order['id']]);
                            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($items as $item):
                        ?>
                        <div class="summary-item">
                            <div class="item-info">
                                <span class="item-quantity"><?php echo $item['quantity']; ?> ×</span>
                                <span class="item-name"><?php echo htmlspecialchars($item['product_name']); ?></span>
                            </div>
                            <div class="item-price"><?php echo number_format($item['total'], 2); ?> DH</div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="summary-totals">
                        <div class="subtotal">
                            <span>Sous-total:</span>
                            <span><?php echo number_format($order['subtotal'], 2); ?> DH</span>
                        </div>
                        <div class="delivery-fee">
                            <span>Frais de livraison:</span>
                            <span>
                                <?php echo($order['delivery_fee'] > 0) ? number_format($order['delivery_fee'], 2) . ' DH' : 'Gratuit'; ?>
                            </span>
                        </div>
                        <div class="total">
                            <span>Total:</span>
                            <span><?php echo number_format($order['total'], 2); ?> DH</span>
                        </div>
                    </div>
                </div>
                <div class="next-steps">
                    <div class="next-step-item">
                        <div class="step-icon">
                            <img src="icons/phone.png" alt="Phone">
                        </div>
                        <div class="step-text">
                            <h5>Confirmation par téléphone</h5>
                            <p>Notre équipe vous appellera bientôt pour confirmer votre commande.</p>
                        </div>
                    </div>
                    <div class="next-step-item">
                        <div class="step-icon">
                            <img src="icons/delivery.png" alt="Delivery">
                        </div>
                        <div class="step-text">
                            <h5>Livraison estimée</h5>
                            <p>Votre commande vous sera livrée dans 45-60 minutes après confirmation.</p>
                        </div>
                    </div>
                </div>
                <div class="order-actions">
                    <a href="index.php" class="btn btn-home">Retour à l'accueil</a>
                    <a href="menu.php" class="btn btn-menu">Continuer Shopping</a>
                </div>
            </div>
        </div>
    </section>
    <?php include_once 'footer.php'; ?>
    <script src="js/cart.js"></script>
    <script>
        function copyOrderId() {
        const orderId = document.getElementById('order-id').innerText;
        const tempInput = document.createElement('input');
        tempInput.value = orderId;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        showCartNotification('Numéro de commande copié: ' + orderId);
    }
    </script>
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://unpkg.com/isotope-layout@3.0.4/dist/isotope.pkgd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js"></script>
    <script src="js/custom.js"></script>
</body>
</html>