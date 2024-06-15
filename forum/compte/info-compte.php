<?php
session_start();
require_once '../config.php';
$name = isset($_SESSION['userName']) ? $_SESSION['userName'] : false;
$articles_like = [];
$articles_dislike = [];

$stmt = $bdd->prepare("SELECT * FROM article WHERE auteur = :auteur");
$stmt->execute(['auteur' => $name]);
$published_articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $bdd->prepare("SELECT * FROM votes WHERE user_id = :user_id");
$stmt->execute(['user_id' => $name]);
$votes_articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($votes_articles as $vote) {
    if ($vote['vote_type'] == 'like') {
        $articles_like[] = $vote['article_id'];
    } elseif ($vote['vote_type'] == 'dislike') {
        $articles_dislike[] = $vote['article_id'];
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class='container'>
        <h2>Articles publiés par <?php echo $name; ?></h2>
        <?php foreach ($published_articles as $article) : ?>
            <div class='card mb-3'>
                <div class='card-body'>
                    <h5 class='card-title'><?php echo htmlspecialchars($article['titre']); ?></h5>
                    <p class='card-text'><?php echo htmlspecialchars($article['contenu']); ?></p>
                    <p class='card-text'><em>categorie <?php echo $article['categorie']; ?></em></p>
                </div>
            </div>
        <?php endforeach; ?>

        <h2>Articles likés par <?php echo $name; ?></h2>
        <?php foreach ($articles_like as $liked) :
            $stmt = $bdd->prepare("SELECT * FROM article WHERE id = :id");
            $stmt->execute(['id' => $liked]);
            $like = $stmt->fetch(PDO::FETCH_ASSOC); ?>
            <div class='card mb-3'>
                <div class='card-body'>
                    <h5 class='card-title'><?php echo htmlspecialchars($like['titre']); ?></h5>
                    <p class='card-text'><?php echo htmlspecialchars($like['contenu']); ?></p>
                    <p class='card-text'><em>categorie <?php echo $like['categorie']; ?></em></p>
                </div>
            </div>
        <?php endforeach; ?>

        <h2>Articles dislikés par <?php echo $name; ?></h2>
        <?php foreach ($articles_dislike as $disliked) :
            $stmt = $bdd->prepare("SELECT * FROM article WHERE id = :id");
            $stmt->execute(['id' => $disliked]);
            $dislike = $stmt->fetch(PDO::FETCH_ASSOC); ?>
            <div class='card mb-3'>
                <div class='card-body'>
                    <h5 class='card-title'><?php echo htmlspecialchars($dislike['titre']); ?></h5>
                    <p class='card-text'><?php echo htmlspecialchars($dislike['contenu']); ?></p>
                    <p class='card-text'><em>categorie <?php echo $dislike['categorie']; ?></em></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>
