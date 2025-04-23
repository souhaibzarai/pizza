<?php
    require_once 'db.php';

    /**
     * Function to fetch footer settings
     * @return array Associative array containing footer settings
     */
    function getFooterSettings()
    {
        global $pdo;
        try {
            $stmt     = $pdo->query("SELECT * FROM footer LIMIT 1");
            $settings = $stmt->fetch(PDO::FETCH_ASSOC);
            return $settings ? array_map('htmlspecialchars', $settings) : [];
        } catch (PDOException $e) {
            die("Erreur lors de la récupération des paramètres du pied de page : " . $e->getMessage());
        }
    }

    // Fetch footer settings
    $footer_settings = getFooterSettings();
?>

<footer class="footer_section">
  <div class="container">
    <div class="footer_content">

      <!-- Contact Section -->
      <div class="footer-col">
        <div class="footer_contact">
          <h4>Contactez-nous</h4>
          <div class="contact_link_box">
            <!-- Address -->
            <?php if (! empty($footer_settings['address'])): ?>
              <a href="#" class="contact-link">
                <div class="icon-container">
                  <img src="icons/location.png" alt="Location icon" class="contact-icon">
                </div>
                <span><?php echo $footer_settings['address']; ?></span>
              </a>
            <?php endif; ?>

            <!-- Phone -->
            <?php if (! empty($footer_settings['phone'])): ?>
              <a href="tel:<?php echo $footer_settings['phone']; ?>" class="contact-link">
                <div class="icon-container">
                  <img src="icons/phone.png" alt="Phone icon" class="contact-icon">
                </div>
                <span><?php echo $footer_settings['phone']; ?></span>
              </a>
            <?php endif; ?>

            <!-- Email -->
            <?php if (! empty($footer_settings['email'])): ?>
              <a href="mailto:<?php echo $footer_settings['email']; ?>" class="contact-link">
                <div class="icon-container">
                  <img src="icons/email.png" alt="Email icon" class="contact-icon">
                </div>
                <span><?php echo $footer_settings['email']; ?></span>
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Logo & Social Section -->
      <div class="footer-col">
        <div class="footer_detail">
          <a href="#" class="footer-logo">ELBARAKA</a>
          <p class="footer-tagline">Cuisine authentique et savoureuse</p>
          <div class="footer_social">
            <!-- Facebook -->
            <?php if (! empty($footer_settings['facebook'])): ?>
              <a href="<?php echo $footer_settings['facebook']; ?>" target="_blank" aria-label="Facebook">
                <img src="icons/facebook.png" alt="Facebook icon" style="width: 22px">
              </a>
            <?php endif; ?>

            <!-- Instagram -->
            <?php if (! empty($footer_settings['instagram'])): ?>
              <a href="<?php echo $footer_settings['instagram']; ?>" target="_blank" aria-label="Instagram">
                <img src="icons/instagram.png" alt="Instagram icon" style="width: 22px">
              </a>
            <?php endif; ?>

            <!-- Twitter -->
            <?php if (! empty($footer_settings['twitter'])): ?>
              <a href="<?php echo $footer_settings['twitter']; ?>" target="_blank" aria-label="Twitter">
                <img src="icons/twitter.png" alt="Twitter icon" style="width: 22px">
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Hours Section -->
      <div class="footer-col">
        <h4>Heures d'ouverture</h4>
        <div class="hours-container">
          <p><?php echo $footer_settings['hours_days'] ?? 'N/A'; ?></p>
          <p class="hours"><?php echo $footer_settings['hours_time'] ?? 'N/A'; ?></p>
        </div>
      </div>
    </div>

    <!-- Footer Divider -->
    <div class="footer-divider"></div>

    <!-- Copyright Info -->
    <div class="footer-info">
      <p>
        &copy; <span id="displayYear"><?php echo date('Y'); ?></span> ELBARAKA. Tous droits réservés.
      </p>
    </div>
  </div>
</footer>