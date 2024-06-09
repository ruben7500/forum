<?php
session_start();
require_once '../config.php';
$name = isset($_SESSION['userName']) ? $_SESSION['userName'] : false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment']) && isset($_POST['article_id'])) {
    $article_id = $_POST['article_id'];
    $comment = $_POST['comment'];
    if (!empty($comment)) {
        try {
            $sql = "INSERT INTO commentaires (article_id, pseudo, comment) VALUES (:article_id, :pseudo, :comment)";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
            $stmt->bindParam(':pseudo', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->execute();

            header("Location: article-page_traitement.php?id=$article_id");
            exit();
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            exit();
        }
    } else {
        header("Location: article-page_traitement.php?id=$article_id&error=empty_comment");
        exit();
    }
} else {
    header("Location: article-page_traitement.php?error=missing_data");
    exit();
}
?>