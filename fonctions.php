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

// Fonction pour récupérer les commentaires approuvés d'un article
function getComments($pdo, $id_article) {
    $sql = "SELECT * FROM commentaire WHERE id_article = ? AND status = 'approved' ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_article]);
    return $stmt->fetchAll();
}

?>