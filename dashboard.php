<?php
session_start();
require_once('config.php');
require_once('fonctions.php');

// Vérifier si l'utilisateur est connecté
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Vérifier si l'utilisateur a le rôle author ou admin
if (!isAuthor()) {
    header("Location: index.php");
    exit;
}

$id_user = $_SESSION['user_id'];
$success = '';
$error = '';

// Gestion des actions (créer, éditer, supprimer)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Créer un article
    if (isset($_POST['action']) && $_POST['action'] === 'create') {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $img_article = trim($_POST['img_article']);
        $id_category = (int)$_POST['id_category'];
        
        if (!empty($title) && !empty($content) && !empty($img_article)) {
            if (createArticle($pdo, $title, $content, $img_article, $id_category, $id_user)) {
                $success = "Article créé avec succès !";
            } else {
                $error = "Erreur lors de la création de l'article";
            }
        } else {
            $error = "Veuillez remplir tous les champs";
        }
    }
    
    // Éditer un article
    if (isset($_POST['action']) && $_POST['action'] === 'edit') {
        $id_article = (int)$_POST['id_article'];
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $img_article = trim($_POST['img_article']);
        $id_category = (int)$_POST['id_category'];
        
        if (!empty($title) && !empty($content) && !empty($img_article)) {
            if (updateArticle($pdo, $id_article, $title, $content, $img_article, $id_category)) {
                $success = "Article modifié avec succès !";
            } else {
                $error = "Erreur lors de la modification";
            }
        } else {
            $error = "Veuillez remplir tous les champs";
        }
    }
}

// Gestion de la suppression (via GET)
if (isset($_GET['delete'])) {
    $id_article = (int)$_GET['delete'];
    if (deleteArticle($pdo, $id_article)) {
        $success = "Article supprimé avec succès !";
    } else {
        $error = "Erreur lors de la suppression";
    }
}

// Récupération des données
$user = getUserById($pdo, $id_user);
$articles = getUserArticles($pdo, $id_user);
$categories = getAllCategories($pdo);
$nbArticles = countUserArticles($pdo, $id_user);
$nbComments = countUserComments($pdo, $id_user);
$nbCommentsOnArticles = countCommentsOnUserArticles($pdo, $id_user);

// Récupérer l'article à éditer si demandé
$editArticle = null;
if (isset($_GET['edit'])) {
    $editArticle = getArticleById($pdo, (int)$_GET['edit']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard - <?php echo $user['userName']; ?></title>
    
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
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
        .list-group-item {
            border: none;
            background: #f8f9fa;
            margin-bottom: 16px;
            border-radius: 12px;
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
        .alert {
            border-radius: 12px;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="index.php">BlogCMS</a>
            <div class="ms-auto d-flex align-items-center">
                <span class="me-3">Bienvenue, <strong><?php echo $user['userName']; ?></strong></span>
                <a href="index.php" class="btn btn-sm btn-outline-dark me-2">Accueil</a>
                <a href="login.php?action=logout" class="btn btn-sm btn-dark">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        
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

        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="d-flex">
                            <div class="icon icon-shape bg-gradient-dark text-white shadow me-3">
                                <i class="material-icons-round opacity-10">article</i>
                            </div>
                            <div>
                                <p class="text-sm mb-0 text-capitalize">Mes Articles</p>
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
                            <div class="icon icon-shape bg-gradient-primary text-white shadow me-3">
                                <i class="material-icons-round opacity-10">comment</i>
                            </div>
                            <div>
                                <p class="text-sm mb-0 text-capitalize">Mes Commentaires</p>
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
                            <div class="icon icon-shape bg-gradient-success text-white shadow me-3">
                                <i class="material-icons-round opacity-10">forum</i>
                            </div>
                            <div>
                                <p class="text-sm mb-0 text-capitalize">Commentaires Reçus</p>
                                <h4 class="mb-0"><?php echo $nbCommentsOnArticles; ?></h4>
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
                                <p class="text-sm mb-0 text-capitalize">Catégories</p>
                                <h4 class="mb-0"><?php echo count($categories); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Formulaire Créer/Éditer -->
            <div class="col-lg-5 mb-4">
                <div class="card">
                    <div class="card-header pb-0 px-4 pt-4">
                        <h6 class="mb-0"><?php echo $editArticle ? 'Éditer l\'article' : 'Créer un nouvel article'; ?></h6>
                    </div>
                    <div class="card-body px-4 pt-3 pb-4">
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="<?php echo $editArticle ? 'edit' : 'create'; ?>">
                            <?php if ($editArticle): ?>
                                <input type="hidden" name="id_article" value="<?php echo $editArticle['ID_article']; ?>">
                            <?php endif; ?>

                            <div class="mb-3">
                                <label class="form-label">Titre</label>
                                <input type="text" name="title" class="form-control" 
                                       value="<?php echo $editArticle ? $editArticle['title'] : ''; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Contenu</label>
                                <textarea name="content" class="form-control" rows="4" required><?php echo $editArticle ? $editArticle['content'] : ''; ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Image URL</label>
                                <input type="text" name="img_article" class="form-control" 
                                       value="<?php echo $editArticle ? $editArticle['img_article'] : ''; ?>" required>
                                <small class="text-muted">Exemple: assets/images/thumbs/masonry/gallery-1000.jpg</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Catégorie</label>
                                <select name="id_category" class="form-select" required>
                                    <?php foreach($categories as $cat): ?>
                                        <option value="<?php echo $cat['ID_Category']; ?>" 
                                                <?php echo ($editArticle && $editArticle['id_categoy'] == $cat['ID_Category']) ? 'selected' : ''; ?>>
                                            <?php echo $cat['name_category']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-gradient w-100">
                                <?php echo $editArticle ? 'Modifier' : 'Créer'; ?>
                            </button>
                            
                            <?php if ($editArticle): ?>
                                <a href="dashboard.php" class="btn btn-outline-secondary w-100 mt-2">Annuler</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Liste des articles -->
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header pb-0 px-4 pt-4">
                        <h6 class="mb-0">Mes Articles (<?php echo count($articles); ?>)</h6>
                    </div>
                    <div class="card-body px-4 pt-3 pb-4">
                        <?php if (count($articles) > 0): ?>
                            <ul class="list-group">
                                <?php foreach($articles as $article): ?>
                                    <?php $category = getCategoryById($pdo, $article['id_categoy']); ?>
                                    <li class="list-group-item d-flex p-4">
                                        <div class="d-flex flex-column flex-grow-1">
                                            <h6 class="mb-2"><?php echo $article['title']; ?></h6>
                                            <span class="mb-2 text-xs">
                                                Catégorie: <span class="text-dark fw-bold"><?php echo $category['name_category']; ?></span>
                                            </span>
                                            <span class="text-xs">
                                                Date: <span class="text-dark"><?php echo date('d/m/Y', strtotime($article['created_at'])); ?></span>
                                            </span>
                                        </div>
                                        <div class="ms-auto text-end d-flex flex-column">
                                            <a href="dashboard.php?edit=<?php echo $article['ID_article']; ?>" 
                                            class="btn btn-link text-dark px-3 mb-1">
                                                <i class="material-icons-round text-sm me-2">Éditer</i>
                                            </a>
                                            <a href="dashboard.php?delete=<?php echo $article['ID_article']; ?>" 
                                            class="btn btn-link text-danger px-3 mb-0"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                                                <i class="material-icons-round text-sm me-2">Supprimer</i>
                                            </a>
                                        </div>


                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="material-icons-round" style="font-size: 48px; color: #ccc;">article</i>
                                <p class="text-muted mt-3">Vous n'avez pas encore d'articles. Créez-en un !</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
