<?php

    require_once 'db.php';

    function getTestimonials()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM testimonials");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $testimonials = getTestimonials();
?>

<section class="client_section layout_padding-bottom">
    <div class="container">
        <div class="heading_container heading_center psudo_white_primary mb_45">
            <h2>Ce que disent nos clients</h2>
        </div>
        <div class="carousel-wrap row">
            <div class="owl-carousel client_owl-carousel">
                <?php foreach ($testimonials as $testimonial): ?>
                    <div class="item">
                        <div class="box">
                            <div class="detail-box">
                                <p><?php echo htmlspecialchars($testimonial['testimonial']); ?></p>
                                <h6><?php echo htmlspecialchars($testimonial['name']); ?></h6>
                            </div>
                            <div class="img-box">
                                <img src="<?php echo htmlspecialchars($testimonial['image_url']); ?>" alt="" class="box-img" />
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>