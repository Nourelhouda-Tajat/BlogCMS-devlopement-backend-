<?php
session_start();
require_once('config.php');
require_once('fonctions.php');

// Vérifier si l'utilisateur est admin
if (!isLoggedIn() || !isAdmin()) {
    header("Location: index.php");
    exit;
}

$success = '';
$error = '';

// Gestion des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Créer catégorie
    if (isset($_POST['action']) && $_POST['action'] === 'create_category') {
        $name = trim($_POST['name_category']);
        if (!empty($name)) {
            if (createCategory($pdo, $name)) {
                $success = "Catégorie créée avec succès !";
            } else {
                $error = "Erreur lors de la création";
            }
        }
    }
    
    // Éditer catégorie
    if (isset($_POST['action']) && $_POST['action'] === 'edit_category') {
        $id = (int)$_POST['id_category'];
        $name = trim($_POST['name_category']);
        if (!empty($name)) {
            if (updateCategory($pdo, $id, $name)) {
                $success = "Catégorie modifiée avec succès !";
            } else {
                $error = "Erreur lors de la modification";
            }
        }
    }
}

// Gestion des suppressions
if (isset($_GET['delete_category'])) {
    if (deleteCategory($pdo, (int)$_GET['delete_category'])) {
        $success = "Catégorie supprimée !";
    }
}

if (isset($_GET['delete_comment'])) {
    if (deleteComment($pdo, (int)$_GET['delete_comment'])) {
        $success = "Commentaire supprimé !";
    }
}

if (isset($_GET['delete_user'])) {
    if (deleteUser($pdo, (int)$_GET['delete_user'])) {
        $success = "Utilisateur supprimé !";
    }
}

// Récupération des données
$user = getUserById($pdo, $_SESSION['user_id']);
$nbAuthors = countAllAuthors($pdo);
$nbArticles = countAllArticles($pdo);
$nbComments = countAllComments($pdo);
$nbCategories = countAllCategories($pdo);

$categories = getAllCategories($pdo);
$users = getAllUsers($pdo);
$comments = getAllCommentsWithDetails($pdo);

// Section active
$section = $_GET['section'] ?? 'stats';

// Catégorie à éditer
$editCategory = null;
if (isset($_GET['edit_category'])) {
    $editCategory = getCategoryById($pdo, (int)$_GET['edit_category']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Dashboard</title>
    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: #f8f9fa;
        }
        .navbar {
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }
        .sidebar {
            background: #fff;
            min-height: calc(100vh - 80px);
            box-shadow: 2px 0 4px rgba(0,0,0,0.1);
        }
        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #f0f0f0;
            color: #1A73E8;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 24px;
        }
        .icon {
            width: 64px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }
        .bg-gradient-dark {
            background: linear-gradient(195deg, #42424a 0%, #191919 100%);
        }
        .bg-gradient-primary {
            background: linear-gradient(195deg, #EC407A 0%, #D81B60 100%);
        }
        .bg-gradient-success {
            background: linear-gradient(195deg, #66BB6A 0%, #43A047 100%);
        }
        .bg-gradient-info {
            background: linear-gradient(195deg, #49a3f1 0%, #1A73E8 100%);
        }
        .btn-gradient {
            background: linear-gradient(195deg, #49a3f1 0%, #1A73E8 100%);
            color: white;
            border: none;
        }
        .btn-gradient:hover {
            background: linear-gradient(195deg, #1A73E8 0%, #49a3f1 100%);
            color: white;
        }
        .table {
            background: white;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="index.php">BlogCMS Admin</a>
            <div class="ms-auto d-flex align-items-center">
                <span class="me-3">Admin: <strong><?php echo $user['userName']; ?></strong></span>
                <a href="index.php" class="btn btn-sm btn-outline-dark me-2">Site</a>
                <a href="login.php?action=logout" class="btn btn-sm btn-dark">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <div class="p-3">
                    <h6 class="text-muted mb-3">MENU</h6>
                    <a href="?section=stats" class="<?php echo $section == 'stats' ? 'active' : ''; ?>">
                        <i class="material-icons-round" style="font-size: 18px; vertical-align: middle;">Dashboard</i>
                    </a>
                    <a href="?section=categories" class="<?php echo $section == 'categories' ? 'active' : ''; ?>">
                        <i class="material-icons-round" style="font-size: 18px; vertical-align: middle;">Catégories</i> 
                    </a>
                    <a href="?section=comments" class="<?php echo $section == 'comments' ? 'active' : ''; ?>">
                        <i class="material-icons-round" style="font-size: 18px; vertical-align: middle;">Commentaires</i> 
                    </a>
                    <a href="?section=users" class="<?php echo $section == 'users' ? 'active' : ''; ?>">
                        <i class="material-icons-round" style="font-size: 18px; vertical-align: middle;">Utilisateurs</i> 
                    </a>
                </div>
            </div>

            <!-- Contenu principal -->
            <div class="col-md-10 py-4">
                
                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($section == 'stats'): ?>
                    <!-- Statistiques -->
                    <h3 class="mb-4">Statistiques Globales</h3>
                    <div class="row">
                        <div class="col-xl-3 col-sm-6 mb-3">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="d-flex">
                                        <div class="icon icon-shape bg-gradient-dark text-white shadow me-3">
                                            <i class="material-icons-round opacity-10">person</i>
                                        </div>
                                        <div>
                                            <p class="text-sm mb-0">Total Auteurs</p>
                                            <h4 class="mb-0"><?php echo $nbAuthors; ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-sm-6 mb-3">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="d-flex">
                                        <div class="icon icon-shape bg-gradient-primary text-white shadow me-3">
                                            <i class="material-icons-round opacity-10">article</i>
                                        </div>
                                        <div>
                                            <p class="text-sm mb-0">Total Articles</p>
                                            <h4 class="mb-0"><?php echo $nbArticles; ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-sm-6 mb-3">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="d-flex">
                                        <div class="icon icon-shape bg-gradient-success text-white shadow me-3">
                                            <i class="material-icons-round opacity-10">comment</i>
                                        </div>
                                        <div>
                                            <p class="text-sm mb-0">Total Commentaires</p>
                                            <h4 class="mb-0"><?php echo $nbComments; ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-sm-6 mb-3">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="d-flex">
                                        <div class="icon icon-shape bg-gradient-info text-white shadow me-3">
                                            <i class="material-icons-round opacity-10">category</i>
                                        </div>
                                        <div>
                                            <p class="text-sm mb-0">Total Catégories</p>
                                            <h4 class="mb-0"><?php echo $nbCategories; ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php elseif ($section == 'categories'): ?>
                    <!-- Gestion des catégories -->
                    <h3 class="mb-4">Gestion des Catégories</h3>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6><?php echo $editCategory ? 'Modifier' : 'Ajouter'; ?> une catégorie</h6>
                                </div>
                                <div class="card-body">
                                    <form method="POST">
                                        <input type="hidden" name="action" value="<?php echo $editCategory ? 'edit_category' : 'create_category'; ?>">
                                        <?php if ($editCategory): ?>
                                            <input type="hidden" name="id_category" value="<?php echo $editCategory['ID_Category']; ?>">
                                        <?php endif; ?>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Nom de la catégorie</label>
                                            <input type="text" name="name_category" class="form-control" 
                                                   value="<?php echo $editCategory ? $editCategory['name_category'] : ''; ?>" required>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-gradient w-100">
                                            <?php echo $editCategory ? 'Modifier' : 'Créer'; ?>
                                        </button>
                                        
                                        <?php if ($editCategory): ?>
                                            <a href="?section=categories" class="btn btn-outline-secondary w-100 mt-2">Annuler</a>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h6>Liste des catégories (<?php echo count($categories); ?>)</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nom</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($categories as $cat): ?>
                                                <tr>
                                                    <td><?php echo $cat['ID_Category']; ?></td>
                                                    <td><?php echo $cat['name_category']; ?></td>
                                                    <td class="text-end">
                                                        <a href="?section=comments&delete_comment=<?php echo $comment['ID_comment']; ?>" 
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Supprimer ce commentaire ?')">Supprimer</a>
                                                    </td>

                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php elseif ($section == 'comments'): ?>
                    <!-- Gestion des commentaires -->
                    <h3 class="mb-4">Gestion des Commentaires</h3>
                    
                    <div class="card">
                        <div class="card-header">
                            <h6>Tous les commentaires (<?php echo count($comments); ?>)</h6>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Auteur</th>
                                        <th>Article</th>
                                        <th>Commentaire</th>
                                        <th>Date</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($comments as $comment): ?>
                                        <tr>
                                            <td><?php echo $comment['userName'] ?? 'Invité'; ?></td>
                                            <td><?php echo substr($comment['article_title'], 0, 30); ?>...</td>
                                            <td><?php echo substr($comment['content'], 0, 50); ?>...</td>
                                            <td><?php echo date('d/m/Y', strtotime($comment['created_at'])); ?></td>
                                            <td class="text-end">
                                                <a href="?section=comments&delete_comment=<?php echo $comment['ID_Commentaire']; ?>" 
                                                   class="btn btn-sm btn-outline-danger"
                                                   onclick="return confirm('Supprimer ce commentaire ?')">Supprimer</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                <?php elseif ($section == 'users'): ?>
                    <!-- Gestion des utilisateurs -->
                    <h3 class="mb-4">Gestion des Utilisateurs</h3>
                    
                    <div class="card">
                        <div class="card-header">
                            <h6>Tous les utilisateurs (<?php echo count($users); ?>)</h6>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Rôle</th>
                                        <th>Date</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($users as $u): ?>
                                        <tr>
                                            <td><?php echo $u['id_user']; ?></td>
                                            <td><?php echo $u['userName']; ?></td>
                                            <td><?php echo $u['email']; ?></td>
                                            <td><span class="badge bg-primary"><?php echo $u['role']; ?></span></td>
                                            <td><?php echo date('d/m/Y', strtotime($u['created_at'])); ?></td>
                                            <td class="text-end">
                                                <?php if ($u['id_user'] != $_SESSION['user_id']): ?>
                                                    <a href="?section=users&delete_user=<?php echo $u['id_user']; ?>" 
                                                       class="btn btn-sm btn-outline-danger"
                                                       onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
                                                <?php else: ?>
                                                    <span class="text-muted">Vous</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                <?php endif; ?>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
