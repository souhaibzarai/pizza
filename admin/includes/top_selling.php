<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Top Selling Products</h6>
    </div>
    <div class="card-body">
        <table class="table table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Total Sold</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($top_selling_products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo htmlspecialchars($product['total_quantity']); ?></td>
                </tr>
                <?php endforeach; ?>
<?php if (empty($top_selling_products)): ?>
                <tr>
                    <td colspan="2" class="text-center">No data available</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>