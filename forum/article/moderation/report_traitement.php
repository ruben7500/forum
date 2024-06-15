<?php
session_start();
require_once '../../config.php';
$name = isset($_SESSION['userName']) ? $_SESSION['userName'] : false;

    if (isset($_POST['titre']) && isset($_POST['contenu'])) {
        $titre = htmlspecialchars($_POST['titre']);
        $contenu = htmlspecialchars($_POST['contenu']);
        $article_id = htmlspecialchars($_POST['article_id'], ENT_QUOTES, 'UTF-8');

            $insert = $bdd->prepare('INSERT INTO report(pseudo_modo, article_id, titre, contenu) VALUES(:pseudo_modo, :article_id, :titre, :contenu)');
            $insert->execute(array(
                'pseudo_modo' => $name,
                'article_id' => $article_id,
                'titre' => $titre,
                'contenu' => $contenu
            ));
            header('Location: ../../index.php?success');
            exit();
    } else {
        header('Location: ../report_article.php?error=missing_fields');
        exit();
    }
    ?>
    