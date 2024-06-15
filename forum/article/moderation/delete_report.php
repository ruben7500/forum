<?php
session_start();
require_once '../../config.php';

    $articleId = htmlspecialchars($_POST['article_id'], ENT_QUOTES, 'UTF-8');

    $query = $bdd->prepare('DELETE FROM article WHERE id = ?');
    $query->execute([$articleId]);

    $query = $bdd->prepare('DELETE FROM report WHERE article_id = ?');
    $query->execute([$articleId]);

    header('Location: ../../index.php?reg_err=success');
    exit();
?>
