<?php
ini_set('display_errors','off');
session_start();
require_once '../config.php';
$name = $_SESSION['userName'];

if (isset($_POST['titre']) && isset($_POST['contenu']) && isset($_FILES['img'])) {
    $titre = htmlspecialchars($_POST['titre']);
    $contenu = htmlspecialchars($_POST['contenu']);
    $img = $_FILES['img'];

    $allowed_types = array('image/jpeg', 'image/png', 'image/gif');
    if (!in_array($img['type'], $allowed_types)) {
        header('Location: article.php?error=invalid_image_type');
        exit();
    }

    if ($img['size'] > 20 * 1024 * 1024) {
        header('Location: article.php?error=image_too_large');
        exit();
    }

    $upload_dir = '../uploads/';
    $upload_file = $upload_dir . basename($img['name']);
    if (move_uploaded_file($img['tmp_name'], $upload_file)) {
        $insert = $bdd->prepare('INSERT INTO article(titre, contenu, image) VALUES(:titre, :contenu, :image)');
        $insert->execute(array(
            'titre' => $titre,
            'contenu' => $contenu,
            'image' => $upload_file
        ));
        header('Location: ../index.php?success');
        exit();
    } else {
        header('Location: article.php?error=upload_failed');
        exit();
    }
} else {
    header('Location: article.php?error=missing_fields');
    exit();
}
?>

