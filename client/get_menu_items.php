<?php

require_once 'db.php';

$page           = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$category       = isset($_GET['category']) ? $_GET['category'] : '';
$items_per_page = 6;

if ($page < 1) {
    $page = 1;
}

$offset = ($items_per_page * $page) - $items_per_page;

$base_query = "
    FROM menu_items mi
    LEFT JOIN categories c ON mi.category_id = c.id
";

$where_clause = "";
if (! empty($category)) {

    $sanitized_category = strtolower(str_replace('-', ' ', $mysqli->real_escape_string($category)));
    $where_clause       = " WHERE LOWER(REPLACE(c.name, ' ', '-')) = '$sanitized_category'";
}

$count_query  = "SELECT COUNT(*) as total " . $base_query . $where_clause;
$count_result = $mysqli->query($count_query);

if (! $count_result) {
    error_log("Error in count query: " . $mysqli->error);
    echo json_encode(['error' => 'Database error']);
    exit;
}

$total_items = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_items / $items_per_page);

if ($page > $total_pages && $total_pages > 0) {
    $page   = $total_pages;
    $offset = ($items_per_page * $page) - $items_per_page;
}

$items_query = "
    SELECT
        mi.id,
        mi.name AS item_name,
        mi.description,
        mi.price,
        mi.discount,
        mi.image_url,
        c.name AS category_name,
        mi.price - (mi.price * mi.discount / 100) AS discounted_price
    " . $base_query . $where_clause . "
    ORDER BY mi.id
    LIMIT $offset, $items_per_page
";

$items_result = $mysqli->query($items_query);

if (! $items_result) {
    error_log("Error in items query: " . $mysqli->error);
    echo json_encode(['error' => 'Database error']);
    exit;
}

$items_html = '';
if ($items_result->num_rows > 0) {
    while ($item = $items_result->fetch_assoc()) {

        $sanitizedCategoryName = strtolower(str_replace(' ', '-', $item['category_name'] ?? 'uncategorized'));

        $items_html .= '
        <div class="col-sm-6 col-lg-4 all ' . htmlspecialchars($sanitizedCategoryName) . '">
            <div class="box" data-id="' . htmlspecialchars($item['id']) . '">
                <div>
                    <div class="img-box">
                        <img src="' . htmlspecialchars($item['image_url']) . '" alt="' . htmlspecialchars($item['item_name']) . '" />
                    </div>
                    <div class="detail-box">
                        <h5>' . htmlspecialchars($item['item_name']) . '</h5>
                        <p>' . htmlspecialchars($item['description']) . '</p>
                        <div class="options">';

        if ($item['discount'] > 0) {
            $items_html .= '
                <h6 class="text-light">' . htmlspecialchars(number_format($item['discounted_price'], 2)) . ' DH</h6>
                <h6 class="text-danger"><del>' . htmlspecialchars(number_format($item['price'], 2)) . ' DH</del></h6>';
        } else {
            $items_html .= '
                <h6>' . htmlspecialchars(number_format($item['price'], 2)) . ' DH</h6>';
        }

        $items_html .= '
                            <button class="add-to-cart" onclick="addToCart(' . htmlspecialchars($item['id']) . ')">
                                <img src="icons/cart.png" alt="Add to cart" width="22px">
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
    }
} else {
    $items_html = '<div class="col-12 text-center"><p>No menu items found in this category.</p></div>';
}

$pagination_html = '';
if ($total_pages > 1) {
    $pagination_html = '
    <nav aria-label="Menu pagination">
        <ul class="pagination justify-content-center">';

    if ($page > 1) {
        $pagination_html .= '
            <li class="page-item">
                <a class="page-link" href="?page=1' . ($category ? '&category=' . urlencode($category) : '') . '" aria-label="First">
                    <span aria-hidden="true">&laquo;&laquo;</span>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link" href="?page=' . ($page - 1) . ($category ? '&category=' . urlencode($category) : '') . '" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>';
    }

    $start_page = max(1, $page - 2);
    $end_page   = min($total_pages, $start_page + 4);

    if ($end_page - $start_page < 4) {
        $start_page = max(1, $end_page - 4);
    }

    for ($i = $start_page; $i <= $end_page; $i++) {
        $pagination_html .= '
            <li class="page-item ' . ($i == $page ? 'active' : '') . '">
                <a class="page-link" href="?page=' . $i . ($category ? '&category=' . urlencode($category) : '') . '">' . $i . '</a>
            </li>';
    }

    if ($page < $total_pages) {
        $pagination_html .= '
            <li class="page-item">
                <a class="page-link" href="?page=' . ($page + 1) . ($category ? '&category=' . urlencode($category) : '') . '" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link" href="?page=' . $total_pages . ($category ? '&category=' . urlencode($category) : '') . '" aria-label="Last">
                    <span aria-hidden="true">&raquo;&raquo;</span>
                </a>
            </li>';
    }

    $pagination_html .= '
        </ul>
    </nav>';
}

echo json_encode([
    'items'        => $items_html,
    'pagination'   => $pagination_html,
    'total_items'  => $total_items,
    'total_pages'  => $total_pages,
    'current_page' => $page,
]);
