<?php
session_start();
require_once '../../config.php';
$name = isset($_SESSION['userName']) ? $_SESSION['userName'] : false;
?>
<html lang="fr" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Forum</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>
    <body>
        <?php
        $list = $bdd->prepare('SELECT * FROM report');
        $list->execute();
        
        $query = $bdd->prepare('SELECT * FROM membre WHERE pseudo = ?');
        $query->execute([$name]);
        $role = $query->fetch(PDO::FETCH_ASSOC);
        
        if ($list->rowCount() > 0) {
            $liste_profils = "<div class='row'>";
            while ($row = $list->fetch(PDO::FETCH_ASSOC)) {
                $liste_profils .= "<div class='col-md-4 mb-4'>
                                    <div class='card' style='width: 18rem;'>
                                        <div class='card-body'>
                                            <h5 class='card-title'>Pseudo du modérateur : " . $row["pseudo_modo"] . "</h5>
                                            <p class='card-text'>Titre du report : " . $row["titre"] . "</p>
                                            <p class='card-text'>Contenu du report : " . $row["contenu"] . "</p>
                                            <p class='card-text'>Lien de l'article : http://localhost/forum/article/article-page_traitement.php?id=" . $row["article_id"] . "</p>
                                            
                                            <form action='./delete_report.php' method='post'>
                                                <input type='hidden' name='article_id' value='" . htmlspecialchars($row['article_id'], ENT_QUOTES, 'UTF-8') . "'>
                                                <button type='submit' class='btn btn-danger'>Supprimer</button>
                                            </form>

                                            <form action='./annule_report.php' method='post'>
                                                <input type='hidden' name='article_id' value='" . htmlspecialchars($row['article_id'], ENT_QUOTES, 'UTF-8') . "'>
                                                <button type='submit' class='btn btn-warning'>Annuler</button>                                      
                                            </form>";
                $liste_profils .= "</div>
                                </div>
                            </div>";
            }
            $liste_profils .= "</div>";
            echo $liste_profils;
        } else {
            echo "Aucun report trouvé.";
        }        
        ?>
    </body>
</html>
