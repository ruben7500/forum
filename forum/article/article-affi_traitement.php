<?php
session_start();
require_once '../config.php';
$name = isset($_SESSION['userName']) ? $_SESSION['userName'] : false;

if (isset($_GET['category_name'])) {
    $categoryName = htmlspecialchars($_GET['category_name']);

    $query = $bdd->prepare('SELECT * FROM article WHERE categorie = ?');
    $query->execute([$categoryName]);
    $articles = $query->fetchAll(PDO::FETCH_ASSOC);

    $query = $bdd->prepare('SELECT * FROM membre WHERE pseudo = ?');
    $query->execute([$name]);
    $role = $query->fetch(PDO::FETCH_ASSOC);

    if ($articles) {
        foreach ($articles as $article) {
            echo '<div class="article">';
            echo '<h3><a href="./article-page_traitement.php?id=' . $article['id'] . '" class="article-link" data-article-id="' . $article['id'] . '">' . htmlspecialchars($article['titre']) . '</a></h3>';
            echo '<p>' . htmlspecialchars($article['contenu']) . '</p>';
            echo '</div>';
                if (is_array($role) && isset($role['role'])) {
                    if ($role['role'] == 'moderateur') {
                        echo '<form action="./article/moderation/report_article.php" method="post">';
                        echo '<input type="hidden" name="article_id" value="' . htmlspecialchars($article['id'], ENT_QUOTES, 'UTF-8') . '">';
                        echo '<button type="submit" class="btn btn-warning m-1 report-article">Reporter</button>';
                        echo '</form>';
                    } else if ($role['role'] == 'administrateur') {
                        echo '<form action="./article/moderation/delete_article.php" method="post">';
                        echo '<input type="hidden" name="article_id" value="' . htmlspecialchars($article['id'], ENT_QUOTES, 'UTF-8') . '">';
                        echo '<button type="submit" class="btn btn-danger">Supprimer</button>';
                        echo '</form>';
                    }
                }
                echo '<hr>';
            
            
        }
    } else {
        echo 'Aucun article trouvé pour cette catégorie.';
    }
}
?>
