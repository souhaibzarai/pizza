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

    <!-- Checkout Section -->
    <section class="checkout_section layout_padding">
        <div class="container">
            <div class="heading_container heading_center">
                <h2>Finaliser votre commande</h2>
            </div>

            <?php
                // Check if cart is empty
                $cart_data = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];

                if (empty($cart_data)) {
                    // Redirect to cart page if cart is empty
                    echo '<script>window.location.href = "cart.php";</script>';
                    exit;
                }

                require_once 'db.php';
               // Get cart items and total
$cart_items = [];
$cart_total = 0;

// Prepare placeholders for SQL IN clause
$placeholders = implode(',', array_fill(0, count(array_keys($cart_data)), '?'));

if (! empty($placeholders)) {
    $stmt = $pdo->prepare("
        SELECT id, name, price, discount, image_url
        FROM menu_items
        WHERE id IN ($placeholders)
    ");
    $stmt->execute(array_keys($cart_data));
    $menu_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($menu_items as $item) {
        // Calculate the discounted price
        $discounted_price = $item['price'] - ($item['price'] * $item['discount'] / 100);
        
        // Get the quantity from the cart data
        $quantity   = $cart_data[$item['id']];
        
        // Calculate the item total using the discounted price
        $item_total = $discounted_price * $quantity;
        
        // Add to the cart total
        $cart_total += $item_total;

        // Add the item details to the cart items array
        $cart_items[] = [
            'id'         => $item['id'],
            'name'       => $item['name'],
            'price'      => $discounted_price,  // Using the discounted price
            'quantity'   => $quantity,
            'item_total' => $item_total,
        ];
    }
}


                // Calculate delivery fee
                $delivery_fee        = ($cart_total > 100) ? 0 : 20;
                $total_with_delivery = $cart_total + $delivery_fee;

                // Process form submission
                $errors   = [];
                $success  = false;
                $order_id = '';

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Validate form fields
                    $required_fields = ['full_name', 'id_card', 'phone', 'email', 'address'];
                    $form_data       = [];

                    foreach ($required_fields as $field) {
                        if (empty($_POST[$field])) {
                            $errors[] = "Le champ " . str_replace('_', ' ', $field) . " est requis.";
                        } else {
                            $form_data[$field] = trim($_POST[$field]);
                        }
                    }

                    // Validate email
                    if (! empty($form_data['email']) && ! filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
                        $errors[] = "L'adresse e-mail n'est pas valide.";
                    }

                    // Validate phone (simple check for numeric and length)
                    if (! empty($form_data['phone']) && (! is_numeric(str_replace(['+', ' ', '-'], '', $form_data['phone'])) || strlen($form_data['phone']) < 8)) {
                        $errors[] = "Le numéro de téléphone n'est pas valide.";
                    }

                    $form_data['notes'] = isset($_POST['notes']) && ! empty(trim($_POST['notes'])) ? trim($_POST['notes']) : null;

                    // If no errors, save order to database
                    if (empty($errors)) {
                        try {
                            // Generate unique order ID (current timestamp + random number)
                            $order_id = 'ORD-' . time() . '-' . rand(1000, 9999);

                            // Start transaction
                            $pdo->beginTransaction();

                            // Insert order into orders table
                            $stmt = $pdo->prepare("
                            INSERT INTO orders (
                                order_id, full_name, id_card, phone, email, address, notes,
                                subtotal, delivery_fee, total, status, order_date
                            ) VALUES (
                                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW()
                            )
                        ");

                            $stmt->execute([
                                $order_id,
                                $form_data['full_name'],
                                $form_data['id_card'],
                                $form_data['phone'],
                                $form_data['email'],
                                $form_data['address'],
                                $form_data['notes'],
                                $cart_total,
                                $delivery_fee,
                                $total_with_delivery,
                            ]);

                            $order_db_id = $pdo->lastInsertId();

                            // Insert order items
                            $stmt = $pdo->prepare("
                            INSERT INTO order_items (
                                order_id, product_id, product_name, price, quantity, total
                            ) VALUES (?, ?, ?, ?, ?, ?)
                        ");

                            foreach ($cart_items as $item) {
                                $stmt->execute([
                                    $order_db_id,
                                    $item['id'],
                                    $item['name'],
                                    $item['price'],
                                    $item['quantity'],
                                    $item['item_total'],
                                ]);
                            }

                            // Commit transaction
                            $pdo->commit();

                            // Clear cart
                            setcookie('cart', '', time() - 3600, '/');

                            // Redirect to confirmation page
                            header("Location: order_confirmation.php?order_id=" . urlencode($order_id));
                            exit;

                        } catch (Exception $e) {
                            $pdo->rollBack();
                            $errors[] = "Une erreur s'est produite lors de l'enregistrement de votre commande. Veuillez réessayer.";
                        }
                    }
                }
            ?>

            <div class="checkout-container">
                <div class="row">
                    <!-- Checkout Form -->
                    <div class="col-lg-8">
                        <div class="checkout-form-container">
                            <h4>Informations de livraison</h4>

                            <?php if (! empty($errors)): ?>
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <?php foreach ($errors as $error): ?>
                                            <li><?php echo htmlspecialchars($error); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="" id="checkout-form">
                                <div class="form-group">
                                    <label for="full_name">Nom Complet *</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>" >
                                </div>

                                <div class="form-group">
                                    <label for="id_card">Numéro de Carte d'Identité *</label>
                                    <input type="text" class="form-control" id="id_card" name="id_card" value="<?php echo isset($_POST['id_card']) ? htmlspecialchars($_POST['id_card']) : ''; ?>" >
                                </div>

                                <div class="form-group">
                                    <label for="phone">Numéro de Téléphone *</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" >
                                    <small class="form-text text-muted">Nous vous appellerons pour confirmer votre commande</small>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" >
                                </div>

                                <div class="form-group">
                                    <label for="address">Adresse de Livraison *</label>
                                    <textarea class="form-control" id="address" name="address" rows="3" ><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="notes">Notes supplémentaires (optionnel)</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="2"><?php echo isset($_POST['notes']) ? htmlspecialchars($_POST['notes']) : ''; ?></textarea>
                                </div>

                                <div class="payment-method">
                                    <h5>Mode de Paiement</h5>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="cash_on_delivery" name="payment_method" class="custom-control-input" value="cash" checked>
                                        <label class="custom-control-label" for="cash_on_delivery">
                                            <span class="payment-icon"><img src="icons/cash.png" alt="Cash" width="24"></span>
                                            Paiement à la livraison
                                        </label>
                                    </div>
                                </div>

                                <div class="form-check my-3">
                                    <input type="checkbox" class="form-check-input" id="terms" name="terms" >
                                    <label class="form-check-label" for="terms">
                                        J'accepte les conditions générales et la politique de confidentialité <span style="color:red;">*</span>
                                    </label>
                                </div>
                                <div class="summary-actions">
                                    <button  type="submit" class="btn btn-checkout">Commander</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="col-lg-4">
                        <div class="order-summary">
                            <h4>Résumé de votre commande</h4>

                            <div class="order-items">
                                <?php foreach ($cart_items as $item): ?>
                                    <div class="order-item">
                                        <div class="item-name">
                                            <span class="quantity"><?php echo $item['quantity']; ?> ×</span>
                                            <?php echo htmlspecialchars($item['name']); ?>
                                        </div>
                                        <div class="item-price">
                                            <?php echo number_format($item['item_total'], 2); ?> DH
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="summary-totals">
                                <div class="summary-item">
                                    <span>Sous-total</span>
                                    <span><?php echo number_format($cart_total, 2); ?> DH</span>
                                </div>

                                <div class="summary-item">
                                    <span>Frais de livraison</span>
                                    <span>
                                        <?php echo($delivery_fee > 0) ? number_format($delivery_fee, 2) . ' DH' : 'Gratuit'; ?>
                                    </span>
                                </div>

                                <div class="summary-divider"></div>

                                <div class="summary-total">
                                    <span>Total</span>
                                    <span><?php echo number_format($total_with_delivery, 2); ?> DH</span>
                                </div>
                            </div>

                            <div class="delivery-info">
                                <div class="delivery-icon">
                                    <img src="icons/delivery.png" alt="Delivery" width="32">
                                </div>
                                <div class="delivery-text">
                                    <p>Livraison estimée: <strong>45-60 minutes</strong></p>
                                    <p class="text-muted small">Les délais peuvent varier en fonction de la distance et du trafic</p>
                                </div>
                            </div>

                            <div class="order-notes">
                                <p><i class="fa fa-info-circle"></i> Un membre de notre équipe vous appellera pour confirmer votre commande.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include_once 'footer.php'; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation
        const form = document.getElementById('checkout-form');

        form.addEventListener('submit', function(e) {
            let isValid = true;

            // Validate full name
            const fullName = document.getElementById('full_name').value.trim();
            if (fullName === '') {
                isValid = false;
                document.getElementById('full_name').classList.add('is-invalid');
            } else {
                document.getElementById('full_name').classList.remove('is-invalid');
            }

            // Validate ID card
            const idCard = document.getElementById('id_card').value.trim();
            if (idCard === '') {
                isValid = false;
                document.getElementById('id_card').classList.add('is-invalid');
            } else {
                document.getElementById('id_card').classList.remove('is-invalid');
            }

            // Validate phone
            const phone = document.getElementById('phone').value.trim();
            if (phone === '' || phone.length < 8) {
                isValid = false;
                document.getElementById('phone').classList.add('is-invalid');
            } else {
                document.getElementById('phone').classList.remove('is-invalid');
            }

            // Validate email
            const email = document.getElementById('email').value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email === '' || !emailRegex.test(email)) {
                isValid = false;
                document.getElementById('email').classList.add('is-invalid');
            } else {
                document.getElementById('email').classList.remove('is-invalid');
            }

            // Validate address
            const address = document.getElementById('address').value.trim();
            if (address === '') {
                isValid = false;
                document.getElementById('address').classList.add('is-invalid');
            } else {
                document.getElementById('address').classList.remove('is-invalid');
            }

            // Validate terms checkbox
            const terms = document.getElementById('terms');
            if (!terms.checked) {
                isValid = false;
                terms.classList.add('is-invalid');
            } else {
                terms.classList.remove('is-invalid');
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    });
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