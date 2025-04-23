<?php
    session_start();

    if (! isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    require_once 'db.php';

    // Fetch categories
    $stmt       = $pdo->query("SELECT * FROM categories ORDER BY created_at DESC");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    include 'add_category.php';
?>
<?php include_once 'includes/header.php'; ?>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Gérer Catégories</h2>
        <?php if (! empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
<?php if (isset ($success)): ?>
            <div class="alert alert-success">
                Catégorie ajoutée avec succès.
            </div>
        <?php endif; ?>
        <div class="card mb-4">
            <div class="card-header">
                Ajouter une Catégorie
            </div>
            <div class="card-body">
                <form method="POST" action="" id="add-category-form">
                    <div class="form-group mb-3">
                        <label for="name">Nom de la Catégorie *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-block w-100">Ajouter</button>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                Liste des Catégories
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Date de Création</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($category['id']); ?></td>
                                <td><?php echo htmlspecialchars($category['name']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($category['created_at'])); ?></td>
                                <td>
                                    <a href="edit_category.php?id=<?php echo htmlspecialchars($category['id']); ?>" class="btn btn-info btn-sm">Éditer</a>
                                    <a href="delete_category.php?id=<?php echo htmlspecialchars($category['id']); ?>" class="btn btn-danger btn-sm">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php include_once 'includes/footer.php'; ?>