<?php

// Fonction pour récupérer tous les articles
function getAllArticles($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM article ORDER BY created_at DESC");
    $stmt->execute();
    return $stmt->fetchAll();
}

// Fonction pour récupérer une catégorie par ID
function getCategoryById($pdo, $id) {
    $sql = "SELECT * FROM category WHERE ID_Category = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Fonction pour récupérer un article par ID
function getArticleById($pdo, $id) {
    $sql = "SELECT * FROM article WHERE ID_article = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Fonction pour récupérer un utilisateur par ID
function getUserById($pdo, $id) {
    $sql = "SELECT * FROM utilisateur WHERE id_user = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Fonction pour récupérer les commentaires d'un article
function getComments($pdo, $id_article) {
    $sql = "SELECT * FROM commentaire WHERE id_article = ? ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_article]);
    return $stmt->fetchAll();
}

// Fonction pour ajouter un commentaire
function addComment($pdo, $content, $id_article, $id_user = null) {
    $sql = "INSERT INTO commentaire (content, created_at, id_article, status, id_user) 
            VALUES (?, NOW(), ?, 'approved', ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$content, $id_article, $id_user]);
}

// Fonction pour récupérer un utilisateur par email
function getUserByEmail($pdo, $email) {
    $sql = "SELECT id_user, userName, email, userPassword, role FROM utilisateur WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    return $stmt->fetch();
}

// Fonction pour vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Fonction pour vérifier si l'utilisateur est admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Fonction pour vérifier si l'utilisateur est auteur ou admin
function isAuthor() {
    return isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'author');
}

// Fonction pour se déconnecter
function logout() {
    session_destroy();
    header("Location: index.php");
    exit;
}

?>
