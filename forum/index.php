<?php
ini_set('display_errors', 'off');
session_start();
require_once 'config.php';

if (isset($_SESSION['userName']) && !empty($_SESSION['userName'])) {
    $name = htmlspecialchars($_SESSION['userName']);
    $info = $bdd->prepare("SELECT pseudo, email, password FROM membre WHERE pseudo = ?");
    $info->execute([$name]);
    $data = $info->fetch();
    if (!$data) {
        header('Location: connexion.php?login_err=user_not_found');
        exit();
    }
} else {
    header('Location: connexion.php?login_err=session');
    exit();
}

function htmlsimple($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Forum</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .container-fluid {
            padding: 20px;
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

<div class="container-fluid">
    <div class="row banner">
        <div class="col-10 text-center">
            <h1>Forum jeu vidéo</h1>
        </div>
        <div class="col-2 text-right">
          <a href="./compte/connexion.php" class="btn btn-outline-secondary">Connexion</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 category-list">
            <h2>Catégories</h2>
            <ul class="list-unstyled">
              <?php
                $query = $bdd->query('SELECT nom FROM catégorie');
                $categories = $query->fetchAll(PDO::FETCH_ASSOC);
              ?>
              <?php foreach ($categories as $category): ?>
                <li class="category-item"><a href="#"><?= htmlspecialchars($category['nom']) ?></a></li>
                <?php endforeach; ?>
            </ul>
            <div class="col text-center">
              <a href="./catégorie/catégorie.php" class="btn btn-outline-secondary">Créer catégorie</a>
            </div>
        </div>

        <div class="col-md-9 article-list">
            <h2>Derniers Articles</h2>
            <div class="col text-center">
              <a href="./article/article.php" class="btn btn-outline-secondary">Publier article</a>
            </div>
            <?php
                $maj = $bdd->prepare("SELECT id, id_patch, paragraphe, nbr_para, titre, img FROM patch_note WHERE nbr_para = 1 ORDER BY id_patch DESC LIMIT 0, 10");
                $maj->execute();
                while ($note = $maj->fetch()) {
                    $idn = $note['id_patch'];
                    $para = htmlsimple($note['paragraphe']);
                    $titre = htmlsimple($note['titre']);
                    $img = htmlsimple($note['img']);
            ?>
            <hr>
            <div class="patch-note">
                <a href="patch_note.php?note=<?php echo $idn; ?>" class="text-decoration-none">
                    <div class="row">
                        <div class="col-md-4">
                            <img class="patch-img img-fluid" src="<?php echo $img; ?>" alt="Patch Image">
                        </div>
                        <div class="col-md-8">
                            <div class="patch-details">
                                <h3 class="patch-title"><?php echo $titre; ?></h3>
                                <p class="patch-paragraph"><?php echo $para; ?></p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
</body>
</html>
