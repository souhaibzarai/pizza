<?php

    require_once 'db.php';

    function getPromotions()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM promotions ORDER BY day_of_week");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $promotions = getPromotions();

    $currentDay = date('w');

    $currentDayPromotions = array_filter($promotions, function ($promo) use ($currentDay) {
        return $promo['day_of_week'] == $currentDay;
    });

if (! empty($currentDayPromotions)): ?>
    <div class="promo-container">
        <?php foreach ($currentDayPromotions as $promo): ?>
            <div class="promo-card" data-day="<?php echo htmlspecialchars($promo['day_of_week']); ?>">
                <h2><?php echo htmlspecialchars($promo['title']); ?></h2>
                <p><strong>Jour:</strong>                                                                                                                                                                                                                                                                                                                                                                                  <?php echo htmlspecialchars($promo['day_name']); ?></p>
                <?php
                    $description = str_replace(
                        '{highlight}',
                        '<span class="highlight">' . htmlspecialchars($promo['highlight_text']) . '</span>',
                        htmlspecialchars($promo['description'])
                    );
                    echo $description;
                ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script>

    const today = new Date().getDay();
    const cards = document.querySelectorAll(".promo-card");

    cards.forEach((card) => {
        const cardDay = parseInt(card.getAttribute("data-day"));

        if (cardDay === today) {
            card.classList.add("visible");
        } else {
            card.classList.add("hidden");
        }
    });
</script>