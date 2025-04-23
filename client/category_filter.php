<?php
    require_once 'db.php';

    function getCategories()
    {
        global $pdo;
        try {
            // Fetch only categories that have at least one associated menu item
            $stmt = $pdo->query("
            SELECT c.id, c.name
            FROM categories c
            INNER JOIN menu_items mi ON c.id = mi.category_id
            GROUP BY c.id, c.name
        ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log the error and return an empty array
            error_log("Error fetching categories: " . $e->getMessage());
            return [];
        }
    }

    $categories = getCategories();
?>

<ul class="filters_menu">
    <li class="active" data-filter="*">Tout</li>
    <?php foreach ($categories as $category): ?>
<?php
    // Sanitize category name for use in HTML classes and data attributes
    $sanitizedCategoryName = strtolower(str_replace(' ', '-', $category['name']));
?>
        <li data-filter=".<?php echo htmlspecialchars($sanitizedCategoryName); ?>">
            <?php echo htmlspecialchars($category['name']); ?>
        </li>
    <?php endforeach; ?>
</ul>