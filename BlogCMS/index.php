<?php
// admin_dashboard.php
session_start();

// Inclusion du fichier de connexion (même dossier)
require_once('config.php');

// Compter les éléments pour le dashboard
$sqlArticles = "SELECT COUNT(*) FROM article";
$sqlCategories = "SELECT COUNT(*) FROM category";
$sqlUsers = "SELECT COUNT(*) FROM utilisateur";

$nbArticles = $conn->query($sqlArticles)->fetchColumn();
$nbCategories = $conn->query($sqlCategories)->fetchColumn();
$nbUsers = $conn->query($sqlUsers)->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body>
<!-- header admin -->
<div class="header_section">
    <div class="container-fluid">
        <div class="header_main">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="logo" href="index.html"><img src="assets/images/logo.png"></a>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item"><a class="nav-link" href="index.html">Site</a></li>
                        <li class="nav-item active"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="articles.php">Articles</a></li>
                        <li class="nav-item"><a class="nav-link" href="categories.php">Catégories</a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>

<div class="container mt-5">
    <h1>Dashboard administrateur</h1>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card p-3 text-center">
                <h3>Articles</h3>
                <p class="display-4"><?php echo $nbArticles; ?></p>
                <a href="articles.php" class="btn btn-primary">Gérer</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 text-center">
                <h3>Catégories</h3>
                <p class="display-4"><?php echo $nbCategories; ?></p>
                <a href="categories.php" class="btn btn-primary">Gérer</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 text-center">
                <h3>Utilisateurs</h3>
                <p class="display-4"><?php echo $nbUsers; ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Scripts bootstrap -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
