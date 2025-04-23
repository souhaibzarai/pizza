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

    <!-- Order Status Section -->
    <section class="order_status_section layout_padding">
        <div class="container">
            <div class="heading_container heading_center">
                <h2>Vérifier le statut de votre commande</h2>
            </div>
            <div class="order-status-form">
                <form method="GET" action="">
                    <div class="form-group">
                        <label for="order_id">Numéro de Commande</label>
                        <input type="text" class="form-control" id="order_id" name="order_id" placeholder="Entrez votre numéro de commande" required>
                    </div>
                    <button type="submit" class="btn btn-checkout">Vérifier</button>
                </form>
            </div>
            <?php
                if (isset($_GET['order_id']) && ! empty($_GET['order_id'])) {
                    $search_order_id = $_GET['order_id'];

                    // Get order details from database
                    require_once 'db.php';

                    $stmt = $pdo->prepare("
                    SELECT * FROM orders WHERE order_id = ?
                ");
                    $stmt->execute([$search_order_id]);
                    $order = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($order) {
                    ?>
                        <div class="order-details">
                            <h4>Détails de la Commande</h4>
                            <div class="order-detail">
                                <span>Numéro de commande:</span>
                                <strong><?php echo htmlspecialchars($order['order_id']); ?></strong>
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
                                <span>Statut:</span>
                                <strong><?php echo ucfirst($order['status']); ?></strong>
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
                        <?php
                            } else {
                                    echo '<div class="text-center">';
                                    echo '<h2>Commande introuvable</h2>';
                                    echo '<p>Nous n\'avons pas pu trouver les détails de votre commande.</p>';
                                    echo '</div>';
                                }
                            }
                        ?>
        </div>
    </section>

    <?php include_once 'footer.php'; ?>

    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://unpkg.com/isotope-layout@3.0.4/dist/isotope.pkgd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js"></script>
    <script src="js/custom.js"></script>
</body>
</html>