<?php
// categories.php
session_start();
require_once('config.php');

// 1) AJOUT (CREATE)
if (isset($_POST['action']) && $_POST['action'] == 'add') {
    $name = $_POST['name_category'];
    $description = $_POST['description'];

    $sql = "INSERT INTO category (name_category, description) VALUES (:name, :description)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':description' => $description
    ]);
    header("Location: categories.php");
    exit;
}

// 2) SUPPRESSION (DELETE)
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $sql = "DELETE FROM category WHERE ID_Category = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id]);
    header("Location: categories.php");
    exit;
}

// 3) RÉCUPÉRATION pour modification (READ ONE)
$categoryToEdit = null;
if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $sql = "SELECT * FROM category WHERE ID_Category = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id]);
    $categoryToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// 4) MISE À JOUR (UPDATE)
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    $id = (int) $_POST['id_category'];
    $name = $_POST['name_category'];
    $description = $_POST['description'];

    $sql = "UPDATE category SET name_category = :name, description = :description WHERE ID_Category = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':description' => $description,
        ':id' => $id
    ]);
    header("Location: categories.php");
    exit;
}

// 5) LISTE (READ ALL)
$sql = "SELECT * FROM category ORDER BY ID_Category DESC";
$categories = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin - Catégories</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body>
<div class="header_section">
    <div class="container-fluid">
        <div class="header_main">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="logo" href="admin_dashboard.php">Admin</a>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="articles.php">Articles</a></li>
                        <li class="nav-item active"><a class="nav-link" href="categories.php">Catégories</a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>

<div class="container mt-5">
    <h1>Gestion des catégories</h1>

    <div class="row mt-4">
        <!-- Formulaire ajout/modification -->
        <div class="col-md-5">
            <div class="card p-4">
                <h3><?php echo $categoryToEdit ? "Modifier" : "Ajouter"; ?></h3>
                <form method="post">
                    <?php if ($categoryToEdit): ?>
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id_category" value="<?php echo $categoryToEdit['ID_Category']; ?>">
                    <?php else: ?>
                        <input type="hidden" name="action" value="add">
                    <?php endif; ?>

                    <div class="form-group">
                        <label>Nom de la catégorie</label>
                        <input type="text" name="name_category" class="form-control"
                               value="<?php echo $categoryToEdit ? htmlspecialchars($categoryToEdit['name_category']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" name="description" class="form-control"
                               value="<?php echo $categoryToEdit ? htmlspecialchars($categoryToEdit['description']) : ''; ?>">
                    </div>
                    <button type="submit" class="btn btn-success">
                        <?php echo $categoryToEdit ? "Mettre à jour" : "Ajouter"; ?>
                    </button>
                    <?php if ($categoryToEdit): ?>
                        <a href="categories.php" class="btn btn-secondary">Annuler</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- Liste des catégories -->
        <div class="col-md-7">
            <div class="card p-4">
                <h3>Liste (<?php echo count($categories); ?>)</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td><?php echo $cat['ID_Category']; ?></td>
                            <td><strong><?php echo htmlspecialchars($cat['name_category']); ?></strong></td>
                            <td><?php echo htmlspecialchars($cat['description']); ?></td>
                            <td>
                                <a href="categories.php?edit=<?php echo $cat['ID_Category']; ?>" class="btn btn-sm btn-warning">✏️ Modifier</a>
                                <a href="categories.php?delete=<?php echo $cat['ID_Category']; ?>" class="btn btn-sm btn-danger"
                                   onclick="return confirm('Supprimer cette catégorie ?');">🗑️ Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
