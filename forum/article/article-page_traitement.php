<?php
session_start();
require_once '../config.php';
$name = isset($_SESSION['userName']) ? $_SESSION['userName'] : false;

$article_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

try {
    $sql = "SELECT titre, contenu, image FROM article WHERE id = :id";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':id', $article_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $title = htmlspecialchars($row['titre']);
        $content = htmlspecialchars($row['contenu']);
        $image = $row['image'];

        $reaction_sql = "SELECT 
                            SUM(vote_type = 'like') AS likes, 
                            SUM(vote_type = 'dislike') AS dislikes 
                        FROM votes 
                        WHERE article_id = :article_id";
        $reaction_stmt = $bdd->prepare($reaction_sql);
        $reaction_stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
        $reaction_stmt->execute();
        $reaction = $reaction_stmt->fetch(PDO::FETCH_ASSOC);

        $likes = isset($reaction['likes']) ? $reaction['likes'] : 0;
        $dislikes = isset($reaction['dislikes']) ? $reaction['dislikes'] : 0;
    } else {
        echo "Article non trouvé.";
        exit();
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .article-details {
            margin-bottom: 30px;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .commentaire-section {
            margin-bottom: 20px;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .comment {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            border-radius: 5px;
        }
        .comment .user {
            font-weight: bold;
            color: #007bff;
        }
        .comment .actions {
            margin-top: 10px;
        }
        .comment .actions button {
            margin-right: 10px;
        }
        .actions-section {
            margin-top: 20px;
        }
        .actions-section button {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="article-details text-center">
                    <?php if (!empty($image)): ?>
                        <img src="<?php echo $image; ?>" alt="Image de l'article">
                    <?php endif; ?>
                    <h1><?php echo $title; ?></h1>
                    <p><?php echo nl2br($content); ?></p>
                </div>

                <div class="commentaire-section">
                    <h3>Commentaires</h3>
                    <form action="comment.php" method="post">
                        <input type="hidden" name="article_id" value="<?php echo $article_id; ?>">
                        <div class="form-group">
                            <label for="comment">Ajouter un commentaire :</label>
                            <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
                        </div>
                        <div class="text-center my-3">
                            <?php if ($name): ?>
                                <button type="submit" class="btn btn-primary">Ajouter</button>
                            <?php else: ?>
                                <button type="submit" class="btn btn-primary" disabled>Ajouter</button>
                            <?php endif; ?>
                        </div>
                    </form>

                    <?php
                    $sql = "SELECT * FROM commentaires WHERE article_id = :article_id";
                    $stmt = $bdd->prepare($sql);
                    $stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
                    $stmt->execute();

                    $query = $bdd->prepare('SELECT * FROM membre WHERE pseudo = ?');
                    $query->execute([$name]);
                    $role = $query->fetch(PDO::FETCH_ASSOC);

                    while ($comment = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $comment_user_id = $comment['pseudo'];
                        $comment_text = htmlspecialchars($comment['comment']);
                        echo "<div class='comment'>";
                        echo "<p class='user'>Utilisateur: $comment_user_id</p>";
                        echo "<p>$comment_text</p>";
                        if (is_array($role) && isset($role['role']) && $role['role'] == 'administrateur') {
                            echo '<form action="./moderation/delete_comment.php" method="post">';
                            echo '<input type="hidden" name="commentaire_id" value="' . htmlspecialchars($comment['id'], ENT_QUOTES, 'UTF-8') . '">';
                            echo '<button type="submit" class="btn btn-danger">Supprimer</button>';
                            echo '</form>';
                        }
                        echo "</div>";
                    }
                    ?>
                </div>
                <div class="actions-section text-center">
                    <?php if ($name): ?>
                        <button type="button" id="like-btn" class="btn btn-success">Like</button>
                        <button type="button" id="dislike-btn" class="btn btn-danger">Dislike</button>
                    <?php else: ?>
                        <button type="button" id="like-btn" class="btn btn-success" disabled>Like</button>
                        <button type="button" id="dislike-btn" class="btn btn-danger" disabled  >Dislike</button>
                    <?php endif; ?>
                    <div id="reaction-stats" class="mt-3">Likes: <?php echo $likes; ?> Dislikes: <?php echo $dislikes; ?></div>
                    <div class="mt-3">
                        <a href="../index.php" class="btn btn-secondary">Retour</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('like-btn').addEventListener('click', function() {
            sendReaction('like');
        });

        document.getElementById('dislike-btn').addEventListener('click', function() {
            sendReaction('dislike');
        });

        function sendReaction(reaction) {
            const userId = "<?php echo $name; ?>";

            fetch('like&dislike.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    user_id: userId,
                    article_id: "<?php echo $article_id; ?>",
                    vote_type: reaction
                })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('reaction-stats').innerHTML = `Likes: ${data.likes} Dislikes: ${data.dislikes}`;
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>