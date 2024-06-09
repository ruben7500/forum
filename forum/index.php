<?php
session_start();
require_once 'config.php';
$name = isset($_SESSION['userName']) ? $_SESSION['userName'] : false;
?>

<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Forum</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .container-fluid {
            padding-left: 20px;
        }
        .banner {
            background-color: #f8f9fa;
            padding: 10px;
            margin-bottom: 20px;
        }
        .category-list {
            border-right: 1px solid #dee2e6;
            padding-right: 20px;
        }
        .article-list {
            padding-left: 20px;
        }
        .category-item {
            margin-bottom: 10px;
        }
        .article-item {
            margin-bottom: 20px;
        }
        .patch-img {
            max-width: 100%;
            height: auto;
        }
        .patch-title {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php
    $query = $bdd->query('SELECT id, nom FROM categorie');
    $categories = $query->fetchAll(PDO::FETCH_ASSOC);

    $query = $bdd->prepare('SELECT * FROM membre WHERE pseudo = ?');
    $query->execute([$name]);
    $role = $query->fetch(PDO::FETCH_ASSOC);
    ?>
    <div class="container-fluid">
        <div class="row banner align-items-center">
            <div class="col-8 text-center">
                <h1>Forum jeu vidéo</h1>
            </div>
            <div class="col-2">
                <?php if ($name): ?>
                    <a href="./compte/compte-edit.php">statut : Connecté</a>
                <?php else: ?>
                    <a href="./compte/compte-edit.php">statut : Déconnecté</a>
                <?php endif; ?>
            </div>
            <div class="col-2 text-right">
                <?php
                if (is_array($role) && isset($role['role']) && $role['role'] == 'administrateur') {
                    echo '<a href="./article/moderation/view-report.php" class="btn btn-warning">Voir report</a>';
                }
                ?>
                <a href="./compte/connexion.php" class="btn btn-outline-secondary">Connexion</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 category-list">
                <h2>categories</h2>
                <ul class="list-unstyled">
                    <?php foreach ($categories as $category):
                        ?>
                        <li class="category-item my-3 py-1">
                            <!-- Les categories seront affichés ici -->
                            <button class="category-button btn btn-outline-secondary" data-category-name="<?= htmlspecialchars($category['nom']) ?>"><?= htmlspecialchars($category['nom']) ?></button>
                    <?php 
                                        if (is_array($role) && isset($role['role']) && $role['role'] == 'administrateur') {
                                            echo '<form action="./article/moderation/delete_categorie.php" method="post">';
                                            echo '<input type="hidden" name="categorie_id" value="' . htmlspecialchars($category['id'], ENT_QUOTES, 'UTF-8') . '">';
                                            echo '<button type="submit" class="btn btn-danger">Supprimer</button>';
                                            echo '</form>';
                                        }
                        endforeach; ?>

                        </li>
                </ul>
                <div class="col text-center">
                    <?php if ($name): ?>
                        <a href="./categorie/categorie.php" class="btn btn-outline-secondary">Créer categorie</a>
                    <?php else: ?>
                        <button class="btn btn-outline-secondary" disabled>Créer categorie</button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-9 article-list">
                <div class="text-center col">
                    <?php if ($name): ?>
                        <a href="./article/article.php" class="btn btn-outline-secondary">Publier article</a>
                    <?php else: ?>
                        <button class="btn btn-outline-secondary" disabled>Publier article</button>
                    <?php endif; ?>
                </div>
                <div id="article-affi" class="col-md-12">
                    <!-- Les articles seront affichés ici -->
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.category-button').on('click', function(e) {
                e.preventDefault();
                var categoryName = $(this).data('category-name');
                $.ajax({
                    url: './article/article-affi_traitement.php',
                    type: 'GET',
                    data: { category_name: categoryName },
                    success: function(response) {
                        $('#article-affi').html(response);
                        $('.article-link').on('click', function(e) {
                            e.preventDefault();
                            var articleId = $(this).data('article-id');
                            window.location.href = './article/article-page_traitement.php?id=' + articleId;
                        });
                    },
                    error: function() {
                        alert('Une erreur s\'est produite lors de la récupération des articles.');
                    }
                });
            });
            $(document).on('click', '.article-link', function(e) {
                e.preventDefault();
                var articleId = $(this).data('article-id');
                window.location.href = './article/article-page_traitement.php?id=' + articleId;
            });
        });
    </script>
</body>
</html>
