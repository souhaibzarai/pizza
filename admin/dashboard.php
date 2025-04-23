<?php
    session_start();
    require_once 'db.php';

    // Check if user is logged in
    if (! isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    // Get user information
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Dashboard statistics
    $stats = [
        'menu_items'     => $pdo->query("SELECT COUNT(*) FROM menu_items")->fetchColumn(),
        'categories'     => $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn(),
        'pending_orders' => $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetchColumn(),
        'reservations'   => $pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn(),
        'testimonials'   => $pdo->query("SELECT COUNT(*) FROM testimonials")->fetchColumn(),
    ];

    // Get recent orders
    $stmt          = $pdo->query("SELECT * FROM orders ORDER BY order_date DESC LIMIT 5");
    $recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sales_stmt = $pdo->prepare("
    SELECT DATE(order_date) as order_day, SUM(total) as total_sales
    FROM orders
    WHERE order_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY DATE(order_date)
");
    $sales_stmt->execute();
    $sales_data = $sales_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare data for Chart.js
    $labels   = [];
    $datasets = [];
    foreach ($sales_data as $row) {
        $labels[]   = date('d/m', strtotime($row['order_day']));
        $datasets[] = $row['total_sales'];
    }

    $top_selling_stmt = $pdo->query("
    SELECT name, sales_count as total_quantity
    FROM menu_items
    WHERE sales_count > 0
    ORDER BY sales_count DESC
    LIMIT 5");
    $top_selling_products = $top_selling_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get pending reservations (all reservations sorted by date)
    $pending_reservations_stmt = $pdo->query("
    SELECT * FROM reservations
    ORDER BY reservation_date ASC
    LIMIT 5
");
    $pending_reservations = $pending_reservations_stmt->fetchAll(PDO::FETCH_ASSOC);

    $stats['users'] = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

?>

<?php include_once 'includes/header.php'; ?>
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <span class="btn btn-sm btn-outline-secondary">
                            Welcome,                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             <?php echo htmlspecialchars($user['full_name']); ?>
                        </span>
                    </div>
                </div>
                <?php include_once 'includes/stats_cards.php'; ?>
                <!--  -->
                <?php include_once 'includes/recent_orders.php'; ?>
                <!--  -->
                <?php include_once 'includes/sales_chart.php'; ?>
                <!--  -->
                <?php include_once 'includes/top_selling.php'; ?>
                <!--  -->
                <?php include_once 'includes/pending_reservations.php'; ?>
                <!--  -->

                <?php include_once 'includes/footer.php'; ?>
