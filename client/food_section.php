<?php
    require_once 'db.php';

    function getMenuItems()
    {
        global $pdo;
        try {
            $stmt = $pdo->query("SELECT *, price - (price * discount / 100) AS discounted_price FROM menu_items ORDER BY sales_count LIMIT 9");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Query failed: " . $e->getMessage());
            return [];
        }
    }

    $menuItems = getMenuItems();
?>
<section class="food_section layout_padding-bottom">
    <div class="container">
        <div class="heading_container heading_center">
            <h2>Menu populaire</h2>
        </div>
        <div class="filters-content">
            <div class="row grid">
                <?php if (! empty($menuItems)): ?>
<?php foreach ($menuItems as $item): ?>
                        <div class="col-sm-6 col-lg-4 all">
                            <div class="box">
                                <div>
                                    <div class="img-box">
                                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" />
                                    </div>
                                    <div class="detail-box">
                                        <h5><?php echo htmlspecialchars($item['name']); ?></h5>
                                        <p><?php echo htmlspecialchars($item['description']); ?></p>
                                        <div class="options">
                                            <?php if ($item['discount'] > 0): ?>
                                                <h6 class="text-light"><?php echo htmlspecialchars(number_format($item['discounted_price'], 2)); ?> DH</h6>
                                                <h6 class="text-danger"><del><?php echo htmlspecialchars(number_format($item['price'], 2)); ?> DH</del></h6>
                                            <?php else: ?>
                                                <h6><?php echo htmlspecialchars(number_format($item['price'], 2)); ?> DH</h6>
                                            <?php endif; ?>
                                            <button class="add-to-cart" onclick="addToCart(<?php echo htmlspecialchars($item['id']); ?>)">
                                                <img src="icons/cart.png" alt="Add to cart" width="22px">
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
<?php else: ?>
                    <div class="col-12 text-center">
                        <p>No menu items available.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
