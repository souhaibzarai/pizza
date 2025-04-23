<?php
    session_start();
    // Ensure the user is logged in and is an admin
    if (! isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
    require_once 'db.php'; // Include the database connection

    // Fetch all menu items
    $stmt = $pdo->query("SELECT mi.*, c.name AS category_name FROM menu_items mi LEFT JOIN categories c ON mi.category_id = c.id ORDER BY mi.category_id, mi.name");
    $menu_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all categories for dropdowns
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include_once 'includes/header.php'; ?>
<div class="container-fluid mt-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manage Menu Items</h1>
    </div>
    <div class="row">
        <!-- Add New Menu Item Form -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Add New Menu Item</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="add_menu_item.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name">Item Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="price">Price (DH) <span class="text-danger">*</span></label>
                            <input type="number" step="1" min="0" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="form-group">
                            <label for="discount">Discount (%)</label>
                            <input type="number" step="1" min="0" max="100" class="form-control" id="discount" name="discount" placeholder="0">
                        </div>
                        <div class="form-group">
                            <label for="category_id">Category <span class="text-danger">*</span></label>
                            <select class="form-control" id="category_id" name="category_id" required>
                                <option value="">Select a category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="image">Image</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="image" name="image" accept="image/*">
                                <label class="custom-file-label" for="image">Choose file</label>
                            </div>
                            <small class="form-text text-muted">Recommended size: 300x300px. Max 5MB.</small>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Add Menu Item</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Menu Items List -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">All Menu Items</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Price (DH)</th>
                                    <th>Discount (%)</th>
                                    <th>Category</th>
                                    <th>Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($menu_items)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No menu items found. Add your first item above.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($menu_items as $item): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                                            <td><?php echo htmlspecialchars(substr($item['description'], 0, 100)) . (strlen($item['description']) > 100 ? '...' : ''); ?></td>
                                            <td><?php echo number_format($item['price'], 2); ?> DH</td>
                                            <td><?php echo $item['discount']; ?>% <br>  <?php if ($item['discount'] !== 0): ?>
                                            <?php $discountedPrice = $item['price'] - ($item['price'] * $item['discount'] / 100);?>
                                                <small class="text-success">After Discount: <?php echo number_format($discountedPrice, 2); ?> DH</small>
                                            <?php endif;?></td>
                                            <td><?php echo htmlspecialchars($item['category_name']); ?></td>
                                            <td class="text-center">
                                                <?php if (! empty($item['image_url'])): ?>
                                                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" width="70" height="70" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                                <?php else: ?>
                                                    <span class="text-muted">No image</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <a href="update_menu_item.php?id=<?php echo $item['id']; ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                                <a href="delete_menu_item.php?id=<?php echo $item['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?')"><i class="fas fa-trash"></i> Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once 'includes/footer.php'; ?>
