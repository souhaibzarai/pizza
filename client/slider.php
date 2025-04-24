<?php

    require_once 'db.php';

    function getTopMenuItems()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM menu_items ORDER BY created_at DESC LIMIT 3");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $topMenuItems = getTopMenuItems();
?>
<section class="slider_section">
    <div id="customCarousel1" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <?php foreach ($topMenuItems as $index => $item): ?>
                <div class="carousel-item<?php echo $index === 0 ? ' active' : ''; ?>">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-7 col-lg-6">
                                <div class="detail-box">
                                    <h1><?php echo htmlspecialchars($item['name']); ?></h1>
                                    <p><?php echo htmlspecialchars($item['description']); ?></p>
                                    <div class="btn-box">
                                        <button class="btn1" onclick="addToCart(<?php echo htmlspecialchars($item['id']); ?>)">
		                                            Commander Maintenant
		                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 col-lg-6">
                                <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="" class="img-fluid" />
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Carousel Indicators -->
        <div class="container">
            <ol class="carousel-indicators">
                <?php foreach ($topMenuItems as $index => $item): ?>
                    <li data-target="#customCarousel1" data-slide-to="<?php echo $index; ?>" class="<?php echo $index === 0 ? ' active' : ''; ?>"></li>
                <?php endforeach; ?>
            </ol>
        </div>
    </div>
</section>