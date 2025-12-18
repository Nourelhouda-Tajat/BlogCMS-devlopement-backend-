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

// ==================== NOUVELLES FONCTIONS POUR LE DASHBOARD ====================

// Fonction pour récupérer les articles d'un utilisateur
function getUserArticles($pdo, $id_user) {
    $sql = "SELECT * FROM article WHERE id_user = ? ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_user]);
    return $stmt->fetchAll();
}

// Fonction pour compter les articles d'un utilisateur
function countUserArticles($pdo, $id_user) {
    $sql = "SELECT COUNT(*) as total FROM article WHERE id_user = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_user]);
    return $stmt->fetch()['total'];
}

// Fonction pour compter les commentaires d'un utilisateur
function countUserComments($pdo, $id_user) {
    $sql = "SELECT COUNT(*) as total FROM commentaire WHERE id_user = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_user]);
    return $stmt->fetch()['total'];
}

// Fonction pour compter les commentaires sur les articles d'un utilisateur
function countCommentsOnUserArticles($pdo, $id_user) {
    $sql = "SELECT COUNT(*) as total FROM commentaire 
            WHERE id_article IN (SELECT ID_article FROM article WHERE id_user = ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_user]);
    return $stmt->fetch()['total'];
}

// Fonction pour récupérer toutes les catégories
function getAllCategories($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM category ORDER BY name_category ASC");
    $stmt->execute();
    return $stmt->fetchAll();
}

// Fonction pour créer un article
function createArticle($pdo, $title, $content, $img_article, $id_category, $id_user) {
    $sql = "INSERT INTO article (title, content, img_article, created_at, id_categoy, id_user) 
            VALUES (?, ?, ?, NOW(), ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$title, $content, $img_article, $id_category, $id_user]);
}

// Fonction pour mettre à jour un article
function updateArticle($pdo, $id, $title, $content, $img_article, $id_category) {
    $sql = "UPDATE article SET title = ?, content = ?, img_article = ?, id_categoy = ? 
            WHERE ID_article = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$title, $content, $img_article, $id_category, $id]);
}

// Fonction pour supprimer un article
function deleteArticle($pdo, $id) {
    // Supprimer d'abord les commentaires liés à cet article
    $sql = "DELETE FROM commentaire WHERE id_article = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    
    // Puis supprimer l'article
    $sql = "DELETE FROM article WHERE ID_article = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$id]);
}
// ==================== FONCTIONS ADMIN ====================

// Fonction pour compter tous les auteurs
function countAllAuthors($pdo) {
    $sql = "SELECT COUNT(*) as total FROM utilisateur WHERE role IN ('author', 'admin')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetch()['total'];
}

// Fonction pour compter tous les articles
function countAllArticles($pdo) {
    $sql = "SELECT COUNT(*) as total FROM article";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetch()['total'];
}

// Fonction pour compter tous les commentaires
function countAllComments($pdo) {
    $sql = "SELECT COUNT(*) as total FROM commentaire";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetch()['total'];
}

// Fonction pour compter toutes les catégories
function countAllCategories($pdo) {
    $sql = "SELECT COUNT(*) as total FROM category";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetch()['total'];
}

// Fonction pour récupérer tous les utilisateurs
function getAllUsers($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM utilisateur ORDER BY created_at DESC");
    $stmt->execute();
    return $stmt->fetchAll();
}

// Fonction pour supprimer un utilisateur
function deleteUser($pdo, $id_user) {
    // Supprimer les commentaires de l'utilisateur
    $sql = "DELETE FROM commentaire WHERE id_user = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_user]);
    
    // Supprimer les articles de l'utilisateur
    $sql = "DELETE FROM article WHERE id_user = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_user]);
    
    // Supprimer l'utilisateur
    $sql = "DELETE FROM utilisateur WHERE id_user = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$id_user]);
}

// Fonction pour créer une catégorie
function createCategory($pdo, $name) {
    $sql = "INSERT INTO category (name_category) VALUES (?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$name]);
}

// Fonction pour mettre à jour une catégorie
function updateCategory($pdo, $id, $name) {
    $sql = "UPDATE category SET name_category = ? WHERE ID_Category = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$name, $id]);
}

// Fonction pour supprimer une catégorie
function deleteCategory($pdo, $id) {
    $sql = "DELETE FROM category WHERE ID_Category = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$id]);
}

// Fonction pour récupérer tous les commentaires avec infos article et user
function getAllCommentsWithDetails($pdo) {
    $sql = "SELECT c.ID_comment, c.content, c.created_at, c.id_article, c.id_user, 
                   a.title as article_title, u.userName 
            FROM commentaire c 
            LEFT JOIN article a ON c.id_article = a.ID_article 
            LEFT JOIN utilisateur u ON c.id_user = u.id_user 
            ORDER BY c.created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

// Fonction pour supprimer un commentaire
function deleteComment($pdo, $id) {
    $sql = "DELETE FROM commentaire WHERE ID_comment = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$id]);
}




?>
