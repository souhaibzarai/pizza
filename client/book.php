<?php include_once 'header.php'?>
<body class="sub_page">

  <div class="hero_area">
    <div class="bg-box" style="background: linear-gradient(135deg, #030303, #696868)">
        </div>
    <!-- header section strats -->
    <header class="header_section">
      <div class="container">
        <?php include_once 'nav.php'?>
      </div>
    </header>
    <!-- end header section -->
  </div>

  <!-- book section -->
  <section class="book_section layout_padding">
    <div class="container">
      <div class="heading_container">
        <h2>
          Réserver une Table
        </h2>
      </div>
      <div class="row">
        <?php include_once 'table_order.php'?>
        <div class="map_container ">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3386.0639451322504!2d-4.430351425003927!3d31.932021226689887!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd984b0010e31a47%3A0x3a80500fd17a60d6!2zU25hY2sgQWwgQmFyYWthIHwg2LPZhtin2YMg2KfZhNio2LHZg9ip!5e0!3m2!1sfr!2sma!4v1743861943172!5m2!1sfr!2sma"
            width=100% ;
            height=345px ;
            borderradius=10px ;
            overflow:hidden ;></iframe>
          </div>
      </div>
    </div>
  </section>
  <!-- end book section -->

  <!-- footer section -->
  <?php include_once 'footer.php'?>
  <!-- footer section -->

  <!-- jQery -->
  <script src="js/jquery-3.4.1.min.js"></script>
  <!-- popper js -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
  </script>
  <!-- bootstrap js -->
  <script src="js/bootstrap.js"></script>
  <!-- owl slider -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
  </script>
  <!-- isotope js -->
  <script src="https://unpkg.com/isotope-layout@3.0.4/dist/isotope.pkgd.min.js"></script>
  <!-- nice select -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js"></script>
  <!-- custom js -->
  <script src="js/custom.js"></script>

</body>
</html>