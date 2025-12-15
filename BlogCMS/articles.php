<?php
// articles.php
session_start();
require_once('database.php');

// Récupérer la liste des catégories pour le <select>
$sqlCat = "SELECT ID_Category, name_category FROM category ORDER BY name_category";
$categories = $conn->query($sqlCat)->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la liste des auteurs pour le <select>
$sqlUsers = "SELECT id_user, userName FROM utilisateur WHERE role = 'author' OR role = 'admin' ORDER BY userName";
$users = $conn->query($sqlUsers)->fetchAll(PDO::FETCH_ASSOC);

// 1) AJOUT d'un article (CREATE)
if (isset($_POST['action']) && $_POST['action'] == 'add') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $id_category = $_POST['id_category'];
    $id_user = $_POST['id_user'];
    $status = $_POST['status'];
        $sql = "INSERT INTO article (title, content, created_at, id_user, id_categoy, status, view_count)
            VALUES (:title, :content, NOW(), :id_user, :id_category, :status, 0)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':title' => $title,
        ':content' => $content,
        ':id_user' => $id_user,
        ':id_category' => $id_category,
        ':status' => $status
    ]);
    
    // Message de succès
    $message = "Article ajouté avec succès !";
    header("Location: articles.php?success=add");
    exit;
}
?>