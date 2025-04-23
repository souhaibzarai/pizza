<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (! isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    $userType    = $_SESSION['user_type'];
    $currentPage = basename($_SERVER['PHP_SELF']);

    $page_title = '';
    switch ($currentPage) {
        case 'dashboard.php':
            $page_title = 'Dashboard';
            break;
        case 'categories.php':
            $page_title = 'Categories';
            break;
        case 'menu_items.php':
            $page_title = 'Menu Items';
            break;
        case 'orders.php':
            $page_title = 'Orders';
            break;
        case 'reservations.php':
            $page_title = 'Reservations';
            break;
        case 'promotions.php':
            $page_title = 'Promotions';
            break;
        case 'testimonials.php':
            $page_title = 'Testimonials';
            break;
        case 'about.php':
            $page_title = 'About';
            break;
        case 'manage_footer.php':
            $page_title = 'Footer Settings';
            break;
        case 'manage_users.php':
            $page_title = 'User Management';
            break;
        case 'profile.php':
            $page_title = 'My Profile';
            break;
        default:
            $page_title = '';
    }

?>

<title><?php echo $page_title; ?><?php echo $page_title !== '' ? ' - ' : ''; ?>Admin Panel</title>


<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
            aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link                                                                                                                                                         <?php echo $currentPage == 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">
                        <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link                                                                                                                                                         <?php echo $currentPage == 'categories.php' ? 'active' : '' ?>" href="categories.php">
                        <i class="fas fa-th-list me-1"></i> Categories
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link                                                                                                                                                         <?php echo $currentPage == 'menu_items.php' ? 'active' : '' ?>" href="menu_items.php">
                        <i class="fas fa-utensils me-1"></i> Menu Items
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link                                                                                                                                                         <?php echo $currentPage == 'orders.php' ? 'active' : '' ?>" href="orders.php">
                        <i class="fas fa-shopping-cart me-1"></i> Orders
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link                                                                                                                                                         <?php echo $currentPage == 'reservations.php' ? 'active' : '' ?>" href="reservations.php">
                        <i class="fas fa-calendar-alt me-1"></i> Reservations
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link                                                                                                                                                         <?php echo $currentPage == 'promotions.php' ? 'active' : '' ?>" href="promotions.php">
                        <i class="fas fa-percentage me-1"></i> Promotions
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link                                                                                                                                                         <?php echo $currentPage == 'testimonials.php' ? 'active' : '' ?>" href="testimonials.php">
                        <i class="fas fa-comment me-1"></i> Testimonials
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link                                                                                                                                                         <?php echo $currentPage == 'about.php' ? 'active' : '' ?>" href="about.php">
                        <i class="fas fa-info-circle me-1"></i> About
                    </a>
                </li>

                <li class="nav-item">
                        <a class="nav-link                                                                                                                                                                         <?php echo $currentPage == 'manage_footer.php' ? 'active' : '' ?>" href="manage_footer.php">
                            <i class="fas fa-cog me-1"></i> Footer Settings
                        </a>
                    </li>

                <?php if ($userType === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link                                                                                                                                                                         <?php echo $currentPage == 'manage_users.php' ? 'active' : '' ?>" href="manage_users.php">
                            <i class="fas fa-users me-1"></i> Users
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <!-- Right side: Logout -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'profile.php' ? 'active' : '' ?>" href="profile.php">
                        <i class="fas fa-user-circle me-1"></i> My Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="logout.php">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
