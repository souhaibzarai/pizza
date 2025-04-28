<?php include_once 'header.php'; ?>

<body class="sub_page show-pagination">
    <div class="hero_area">
       <div class="bg-box" style="background: linear-gradient(135deg, #030303, #696868)">
        </div>

        <!-- Header Section -->
        <header class="header_section">
            <div class="container">
                <?php include_once 'nav.php'; ?>
            </div>
        </header>
    </div>

    <!-- Food Section -->
    <section class="food_section layout_padding">
        <div class="container">
            <div class="heading_container heading_center">
                <h2>Notre Menu</h2>
            </div>

            <!-- Category Filters -->
            <?php include_once 'category_filter.php'; ?>

            <!-- Menu Items Container -->
            <div class="filters-content">
                <div class="row grid" id="menu-items-container">
                    <!-- Menu items will be loaded here by AJAX -->
                </div>

                <!-- Pagination Controls -->
                <div class="pagination-container mt-5" id="pagination-container">
                    <!-- Pagination will be loaded here by AJAX -->
                </div>
            </div>
        </div>
    </section>

    <?php include_once 'footer.php'; ?>

    <!-- Scripts -->
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/cart.js"></script>
    <script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            let currentPage = 1;
            let currentCategory = '';


            function loadMenuItems(page = 1, category = '') {

                currentPage = page;
                currentCategory = category;


                document.getElementById('menu-items-container').innerHTML = '<div class="col-12 text-center"><p>Loading...</p></div>';


                $.ajax({
                    url: 'get_menu_items.php',
                    type: 'GET',
                    data: {
                        page: page,
                        category: category
                    },
                    success: function(response) {
                        const data = JSON.parse(response);


                        document.getElementById('menu-items-container').innerHTML = data.items;


                        document.getElementById('pagination-container').innerHTML = data.pagination;


                        initializeIsotope();


                        addPaginationEventListeners();


                        updateURL(page, category);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading menu items:', error);
                        document.getElementById('menu-items-container').innerHTML =
                            '<div class="col-12 text-center"><p>Error loading menu items. Please try again.</p></div>';
                    }
                });
            }


            function initializeIsotope() {
                var grid = document.querySelector('.grid');
                if (!grid) {
                    console.error('Grid element not found!');
                    return;
                }

                var iso = new Isotope(grid, {
                    itemSelector: '.col-sm-6',
                    layoutMode: 'fitRows'
                });
            }


            function addPaginationEventListeners() {
                const paginationLinks = document.querySelectorAll('.pagination .page-link');
                paginationLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();


                        const url = new URL(this.href);
                        const page = url.searchParams.get('page') || 1;


                        loadMenuItems(page, currentCategory);
                    });
                });
            }


            function updateURL(page, category) {
                const url = new URL(window.location.href);


                if (page && page !== 1) {
                    url.searchParams.set('page', page);
                } else {
                    url.searchParams.delete('page');
                }


                if (category) {
                    url.searchParams.set('category', category);
                } else {
                    url.searchParams.delete('category');
                }


                window.history.pushState({page, category}, '', url);
            }


            const filterButtons = document.querySelectorAll('.filters_menu li');
            filterButtons.forEach(function(button) {
                button.addEventListener('click', function() {

                    const filterValue = this.getAttribute('data-filter');


                    let category = '';
                    if (filterValue && filterValue !== '*') {

                        category = filterValue.substring(1);
                    }


                    loadMenuItems(1, category);


                    filterButtons.forEach(function(btn) {
                        btn.classList.remove('active');
                    });
                    this.classList.add('active');
                });
            });


            const urlParams = new URLSearchParams(window.location.search);
            const pageParam = urlParams.get('page') || 1;
            const categoryParam = urlParams.get('category') || '';


            if (categoryParam) {
                filterButtons.forEach(function(btn) {
                    const filterValue = btn.getAttribute('data-filter');
                    if (filterValue === '.' + categoryParam) {
                        btn.classList.add('active');
                    }
                });
            } else {

                filterButtons.forEach(function(btn) {
                    if (btn.getAttribute('data-filter') === '*') {
                        btn.classList.add('active');
                    }
                });
            }


            loadMenuItems(pageParam, categoryParam);


            window.addEventListener('popstate', function(event) {
                if (event.state) {
                    loadMenuItems(event.state.page || 1, event.state.category || '');
                } else {
                    loadMenuItems(1, '');
                }
            });
        });
    </script>
</body>
</html>