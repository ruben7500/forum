<?php
session_start();
require_once '../config.php';
$name = $_SESSION['userName'];
?>
<html lang="fr" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Forum</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>
    <body>
        <?php
        $list = $bdd->prepare('SELECT pseudo, role FROM membre');
        $list->execute();

        if ($list->rowCount() > 0) {
            $liste_profils = "<div class='row'>";
            while ($row = $list->fetch(PDO::FETCH_ASSOC)) {
                $liste_profils .= "<div class='col-md-4 mb-4'>
                                    <div class='card' style='width: 18rem;'>
                                        <div class='card-body'>
                                            <h5 class='card-title'>" . $row["pseudo"] . "</h5>
                                            <p class='card-text'>Rôle: " . $row["role"] . "</p>
                                        </div>
                                    </div>
                                </div>";
            }
            $liste_profils .= "</div>";
            echo $liste_profils;
        } else {
            echo "Aucun profil trouvé.";
        }
        ?>
    </body>
</html>