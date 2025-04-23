<?php
    session_start();

    // Ensure the user is logged in and is an admin
    if (! isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    require_once 'db.php'; // Include the database connection

    // Check if the order id is set
    if (! isset($_GET['id']) || empty($_GET['id'])) {
        echo "Order ID is missing!";
        exit;
    }

                                   // Fetch order details
    $order_id = (int) $_GET['id']; // Get the order id from the URL
    $stmt     = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (! $order) {
        echo "Order not found!";
        exit;
    }

    // Fetch order items
    $stmt = $pdo->prepare("SELECT oi.*, mi.name AS product_name, mi.price FROM order_items oi
                       JOIN menu_items mi ON oi.product_id = mi.id WHERE oi.order_id = ?");
    $stmt->execute([$order_id]);
    $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include_once 'includes/header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Détails de la Commande</h2>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informations de la Commande</h6>
        </div>
        <div class="card-body">
            <table class="table">
                <tr>
                    <th>ID de Commande:</th>
                    <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                </tr>
                <tr>
                    <th>Nom du Client:</th>
                    <td><?php echo htmlspecialchars($order['full_name']); ?></td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td><?php echo htmlspecialchars($order['email']); ?></td>
                </tr>
                <tr>
                    <th>Téléphone:</th>
                    <td><?php echo htmlspecialchars($order['phone']); ?></td>
                </tr>
                <tr>
                    <th>Adresse:</th>
                    <td><?php echo nl2br(htmlspecialchars($order['address'])); ?></td>
                </tr>
                <tr>
                    <th>Notes:</th>
                    <td><?php echo nl2br(htmlspecialchars($order['notes'])); ?></td>
                </tr>
                <tr>
                    <th>Subtotal:</th>
                    <td><?php echo htmlspecialchars($order['subtotal']); ?> DH</td>
                </tr>
                <tr>
                    <th>Frais de Livraison:</th>
                    <td><?php echo htmlspecialchars($order['delivery_fee']); ?> DH</td>
                </tr>
                <tr>
                    <th>Total:</th>
                    <td><?php echo htmlspecialchars($order['total']); ?> DH</td>
                </tr>
                <tr>
                    <th>Status de la Commande:</th>
                    <td>
                        <span class="badge
                            <?php
                                switch ($order['status']) {
                                    case 'pending':
                                        echo 'bg-warning';
                                        break;
                                    case 'confirmed':
                                        echo 'bg-primary';
                                        break;
                                    case 'delivered':
                                        echo 'bg-success';
                                        break;
                                    case 'cancelled':
                                        echo 'bg-danger';
                                        break;
                            }
                            ?>">
                            <?php echo htmlspecialchars(ucfirst($order['status'])); ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Date de Commande:</th>
                    <td><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Articles de la Commande</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nom du Produit</th>
                        <th>Prix Unitaire</th>
                        <th>Quantité</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order_items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($item['price']); ?> DH</td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($item['total']); ?> DH</td>
                        </tr>
                    <?php endforeach; ?>
<?php if (empty($order_items)): ?>
                        <tr>
                            <td colspan="4" class="text-center">Aucun produit trouvé pour cette commande</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <a href="orders.php" class="btn btn-primary">Retour à la liste des commandes</a>
</div>

<?php include_once 'includes/footer.php'; ?>
