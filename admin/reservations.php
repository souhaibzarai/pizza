<?php
    session_start();
    if (! isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
    require_once 'db.php'; // Include the database connection

    // Fetch all reservations
    $stmt         = $pdo->query("SELECT * FROM reservations ORDER BY reservation_date DESC");
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check for status messages from redirects
    $status_messages = [];
    if (isset($_GET['status'])) {
        switch ($_GET['status']) {
            case 'added':
                $status_messages[] = ['type' => 'success', 'text' => 'Reservation added successfully.'];
                break;
            case 'updated':
                $status_messages[] = ['type' => 'success', 'text' => 'Reservation updated successfully.'];
                break;
            case 'deleted':
                $status_messages[] = ['type' => 'success', 'text' => 'Reservation deleted successfully.'];
                break;
        }
    }
?>
<?php include_once 'includes/header.php'; ?>
<div class="container-fluid mt-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manage Reservations</h1>
    </div>

    <!-- Display Messages -->
    <?php foreach ($status_messages as $message): ?>
        <div class="alert alert-<?php echo $message['type']; ?> alert-dismissible fade show" role="alert">
            <?php echo $message['text']; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endforeach; ?>

    <div class="row">
        <!-- Add New Reservation Form -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Add New Reservation</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="add_reservation.php">
                        <div class="form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="guests">Number of Guests <span class="text-danger">*</span></label>
                            <input type="number" min="1" class="form-control" id="guests" name="guests" required>
                        </div>
                        <div class="form-group">
                            <label for="reservation_date">Reservation Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="reservation_date" name="reservation_date" required>
                        </div>
                        <div class="form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="canceled">Canceled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Add Reservation</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reservations List -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">All Reservations</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="reservationsTable" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Guests</th>
                                    <th>Reservation Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($reservations)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No reservations found.</td>
                                    </tr>
                                <?php else: ?>
<?php foreach ($reservations as $reservation): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($reservation['name']); ?></td>
                                            <td><?php echo htmlspecialchars($reservation['phone']); ?></td>
                                            <td><?php echo htmlspecialchars($reservation['email']); ?></td>
                                            <td><?php echo htmlspecialchars($reservation['guests']); ?></td>
                                            <td><?php echo htmlspecialchars($reservation['reservation_date']); ?></td>
                                            <td>
                                                <?php
                                                    switch ($reservation['status']) {
                                                        case 'pending':
                                                            echo '<span class="badge bg-warning">Pending</span>';
                                                            break;
                                                        case 'confirmed':
                                                            echo '<span class="badge bg-success">Confirmed</span>';
                                                            break;
                                                        case 'canceled':
                                                            echo '<span class="badge bg-danger">Canceled</span>';
                                                            break;
                                                        default:
                                                            echo '<span class="badge bg-secondary">Unknown</span>';
                                                            break;
                                                    }
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <!-- Edit Button -->
                                                <a href="update_reservation.php?id=<?php echo $reservation['id']; ?>" class="btn btn-info btn-sm mb-1">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <!-- Delete Button -->
                                                <form method="POST" action="delete_reservation.php" class="d-inline">
                                                    <input type="hidden" name="id" value="<?php echo $reservation['id']; ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this reservation?')">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
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