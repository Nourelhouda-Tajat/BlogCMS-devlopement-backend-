<?php
// articles.php
session_start();
require_once('config.php');

// Récupérer les catégories pour le <select>
$sqlCat = "SELECT ID_Category, name_category FROM category";
$categories = $conn->query($sqlCat)->fetchAll(PDO::FETCH_ASSOC);

// 1) AJOUT d'un article
if (isset($_POST['action']) && $_POST['action'] == 'add') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $id_category = $_POST['id_category'];
    $status = $_POST['status'];
    $id_user = 1; // pour l'instant, tu peux mettre l'id d'un auteur fixe (ex: admin)

    $sql = "INSERT INTO article (title, content, created_at, id_user, id_categoy, status)
            VALUES (:title, :content, NOW(), :id_user, :id_category, :status)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':title' => $title,
        ':content' => $content,
        ':id_user' => $id_user,
        ':id_category' => $id_category,
        ':status' => $status
    ]);
    header("Location: articles.php");
    exit;
}

// 2) SUPPRESSION d'un article
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $sql = "DELETE FROM article WHERE ID_article = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id]);
    header("Location: articles.php");
    exit;
}

// 3) RÉCUPÉRATION d'un article pour modification
$articleToEdit = null;
if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $sql = "SELECT * FROM article WHERE ID_article = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id]);
    $articleToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// 4) MISE À JOUR d'un article
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    $id = (int) $_POST['id_article'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $id_category = $_POST['id_category'];
    $status = $_POST['status'];

    $sql = "UPDATE article 
            SET title = :title,
                content = :content,
                updated_at = NOW(),
                id_categoy = :id_category,
                status = :status
            WHERE ID_article = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':title' => $title,
        ':content' => $content,
        ':id_category' => $id_category,
        ':status' => $status,
        ':id' => $id
    ]);
    header("Location: articles.php");
    exit;
}

// 5) LISTER tous les articles avec jointure sur catégorie
$sql = "SELECT a.*, c.name_category 
        FROM article a
        LEFT JOIN category c ON a.id_categoy = c.ID_Category
        ORDER BY a.created_at DESC";
$articles = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- mobile metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <!-- site metas -->
    <title>Admin - Articles</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- bootstrap css -->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <!-- style css -->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <!-- Responsive-->
    <link rel="stylesheet" href="assets/css/responsive.css">
    <!-- fevicon -->
    <link rel="icon" href="assets/images/fevicon.png" type="image/gif" />
    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="assets/css/jquery.mCustomScrollbar.min.css">
    <!-- Tweaks for older IEs-->
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
</head>
<body>
<!-- header section start -->
<div class="header_section">
    <div class="container-fluid">
        <div class="header_main">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="logo" href="index.html"><img src="assets/images/logo.png"></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="about.html">About</a></li>
                        <li class="nav-item"><a class="nav-link" href="blog.html">Blog</a></li>
                        <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
                        <li class="nav-item active"><a class="nav-link" href="articles.php">Articles</a></li>
                        <li class="nav-item"><a class="nav-link" href="categories.php">Catégories</a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>
<!-- header section end -->

<!-- admin section start -->
<div class="about_section layout_padding">
    <div class="container">
        <h1 class="about_taital">Gestion des articles</h1>
        
        <div class="row mt-4">
            <!-- Liste des articles -->
            <div class="col-lg-8 col-sm-12">
                <h3 class="most_text">Liste des articles</h3>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
                                <th>Catégorie</th>
                                <th>Status</th>
                                <th>Vues</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($articles as $art): ?>
                            <tr>
                                <td><?php echo $art['ID_article']; ?></td>
                                <td><?php echo htmlspecialchars(substr($art['title'], 0, 40)); ?>...</td>
                                <td><?php echo htmlspecialchars($art['name_category']); ?></td>
                                <td>
                                    <?php if ($art['status'] == 'published'): ?>
                                        <span style="color: green;">✓ Publié</span>
                                    <?php else: ?>
                                        <span style="color: gray;">○ Brouillon</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $art['view_count']; ?></td>
                                <td>
                                    <a href="articles.php?edit=<?php echo $art['ID_article']; ?>" class="btn btn-sm btn-warning">Modifier</a>
                                    <a href="articles.php?delete=<?php echo $art['ID_article']; ?>" class="btn btn-sm btn-danger"
                                       onclick="return confirm('Supprimer cet article ?');">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Formulaire ajout/modification -->
            <div class="col-lg-4 col-sm-12">
                <div class="about_main">
                    <h3 class="about_taital"><?php echo $articleToEdit ? "Modifier l'article" : "Ajouter un article"; ?></h3>
                    <form method="post">
                        <?php if ($articleToEdit): ?>
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id_article" value="<?php echo $articleToEdit['ID_article']; ?>">
                        <?php else: ?>
                            <input type="hidden" name="action" value="add">
                        <?php endif; ?>

                        <div class="form-group">
                            <label>Titre</label>
                            <input type="text" name="title" class="form-control email_text"
                                   value="<?php echo $articleToEdit ? htmlspecialchars($articleToEdit['title']) : ''; ?>" 
                                   placeholder="Titre de l'article" required>
                        </div>

                        <div class="form-group">
                            <label>Contenu</label>
                            <textarea name="content" class="form-control massage_text" rows="5" 
                                      placeholder="Contenu de l'article" required><?php 
                                echo $articleToEdit ? htmlspecialchars($articleToEdit['content']) : ''; 
                            ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Catégorie</label>
                            <select name="id_category" class="form-control email_text" required>
                                <option value="">-- Choisir une catégorie --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['ID_Category']; ?>"
                                        <?php
                                        if ($articleToEdit && $articleToEdit['id_categoy'] == $cat['ID_Category']) {
                                            echo 'selected';
                                        }
                                        ?>>
                                        <?php echo htmlspecialchars($cat['name_category']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control email_text">
                                <option value="draft" <?php echo ($articleToEdit && $articleToEdit['status']=='draft')?'selected':''; ?>>Brouillon</option>
                                <option value="published" <?php echo ($articleToEdit && $articleToEdit['status']=='published')?'selected':''; ?>>Publié</option>
                            </select>
                        </div>

                        <div class="send_bt">
                            <button type="submit" class="btn btn-primary">
                                <?php echo $articleToEdit ? "Mettre à jour" : "Ajouter"; ?>
                            </button>
                        </div>
                        
                        <?php if ($articleToEdit): ?>
                            <div class="send_bt" style="margin-top: 10px;">
                                <a href="articles.php" class="btn btn-secondary">Annuler</a>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- admin section end -->

<!-- footer section start -->
<div class="footer_section layout_padding">
    <div class="container">
        <div class="footer_logo"><a href="index.html"><img src="assets/images/footer-logo.png"></a></div>
        <div class="footer_menu">
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="blog.html">Blog</a></li>
                <li><a href="contact.html">Contact us</a></li>
            </ul>
        </div>
        <div class="contact_menu">
            <ul>
                <li><a href="#"><img src="assets/images/call-icon.png"></a></li>
                <li><a href="#">Call : +01 1234567890</a></li>
                <li><a href="#"><img src="assets/images/mail-icon.png"></a></li>
                <li><a href="#">demo@gmail.com</a></li>
            </ul>
        </div>
    </div>
</div>
<!-- footer section end -->

<!-- copyright section start -->
<div class="copyright_section">
    <div class="container">
        <p class="copyright_text">Copyright 2020 All Right Reserved By.<a href="https://html.design"> Free html Templates</a></p>
    </div>
</div>
<!-- copyright section end -->

<!-- Javascript files-->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/jquery-3.0.0.min.js"></script>
<script src="assets/js/plugin.js"></script>
<!-- sidebar -->
<script src="assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="assets/js/custom.js"></script>
</body>
</html>
