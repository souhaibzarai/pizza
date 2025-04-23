<!-- Recent Orders -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Commandes Récentes</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID de Commande</th>
                        <th>Client</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['total']); ?> DH</td>
                        <td>
                            <span class="badge <?php
                                switch ($order['status']) {
                                    case 'pending':
                                        echo 'bg-warning';
                                        break;
                                    case 'confirmed':
                                        echo 'bg-primary';
                                        break;
                                    case 'delivered':
                                        echo 'bg-success';
                                        break;
                                    case 'cancelled':
                                        echo 'bg-danger';
                                        break;
                                }
                            ?>">
                                <?php echo htmlspecialchars(ucfirst($order['status'])); ?>
                            </span>
                        </td>
                        <td><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></td>
                        <td>
                            <a href="order_details.php?id=<?php echo htmlspecialchars($order['id']); ?>" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($recent_orders)): ?>
                    <tr>
                        <td colspan="6" class="text-center">Aucune commande récente trouvée</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="text-center mt-3">
            <a href="orders.php" class="btn btn-primary">Voir Toutes les Commandes</a>
        </div>
    </div>
</div>