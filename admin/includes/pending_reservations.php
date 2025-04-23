<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Pending Reservations</h6>
    </div>
    <div class="card-body">
        <table class="table table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Guests</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pending_reservations as $reservation): ?>
                <tr>
                    <td><?php echo htmlspecialchars($reservation['name']); ?></td>
                    <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($reservation['reservation_date']))); ?></td>
                    <td><?php echo htmlspecialchars($reservation['guests']); ?></td>
                    
                </tr>
                <?php endforeach; ?>
                <?php if (empty($pending_reservations)): ?>
                <tr>
                    <td colspan="4" class="text-center">No pending reservations</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>