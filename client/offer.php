<?php

    require_once 'db.php';

    function getOffers()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM menu_items where discount > 0 ORDER BY discount DESC LIMIT 4");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $offers = getOffers();
?>
<section class="offer_section layout_padding-bottom">
    <div class="container">
        <div class="row">
            <?php foreach ($offers as $offer): ?>
                <div class="col-md-6">
                    <div class="box">
                        <div class="img-box">
                            <img src="<?php echo htmlspecialchars($offer['image_url']); ?>" alt="<?php echo htmlspecialchars($offer['name']); ?>" />
                        </div>
                        <div class="detail-box">
                            <h5><?php echo htmlspecialchars($offer['name']); ?></h5>
                            <h6><span><?php echo htmlspecialchars(number_format($offer['discount'], 0)); ?>%</span> Off</h6>
                            <div class="btn-box">
                                        <button class="btn1" onclick="addToCart(<?php echo htmlspecialchars($offer['id']); ?>)">
		                                            Commander Maintenant
		                                        </button>
                                    </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>