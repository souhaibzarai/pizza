<?php include_once 'header.php'; ?>

<body class="sub_page">
    <div class="hero_area">
        <div class="bg-box" style="background: linear-gradient(135deg, #030303, #696868)">
        </div>
        <!-- Header Section Starts -->
        <header class="header_section">
            <div class="container">
                <?php include_once 'nav.php'; ?>
            </div>
        </header>
        <!-- End Header Section -->
    </div>

    <!-- About Section -->
    <section class="about_section layout_padding">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="img-box">
                        <?php
                            // Fetch about content from the database
                            require_once 'db.php';

                            function getAboutContent()
                            {
                                global $pdo;
                                try {
                                    $stmt = $pdo->query("SELECT * FROM about LIMIT 1");
                                    return $stmt->fetch(PDO::FETCH_ASSOC);
                                } catch (PDOException $e) {
                                    error_log("Error fetching about content: " . $e->getMessage());
                                    return [];
                                }
                            }

                            $about = getAboutContent();

                            if (! empty($about)) {
                                echo '<img src="' . htmlspecialchars($about['image_url']) . '" alt="" />';
                            } else {
                                echo '<p>No image found.</p>';
                            }
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-box">
                        <div class="heading_container">
                            <?php
                                if (! empty($about)) {
                                    echo '<h2>' . htmlspecialchars($about['heading']) . '</h2>';
                                } else {
                                    echo '<h2>About Us</h2>';
                                }
                            ?>
                        </div>
                        <p>
                            <?php
                                if (! empty($about)) {
                                    echo nl2br(htmlspecialchars($about['description']));
                                } else {
                                    echo 'No description available.';
                                }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End About Section -->

    <!-- Footer Section -->
    <?php include_once 'footer.php'; ?>
    <!-- End Footer Section -->

    <!-- Scripts -->
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="js/bootstrap.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://unpkg.com/isotope-layout@3.0.4/dist/isotope.pkgd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js"></script>
    <script src="js/custom.js"></script>
</body>

</html>