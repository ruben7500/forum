<?php
session_start();
require_once '../config.php';
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
        $list = $bdd->prepare('SELECT * FROM membre');
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
                                            <h5 class='card-title'>" . $row["pseudo"] . "</h5>
                                            <p class='card-text'>Rôle: " . $row["role"] . "</p>";
                                            if (is_array($role) && isset($role['role']) && $role['role'] == 'administrateur') {
                                                $liste_profils .=
                                                "<form action='./moderation/upgrade_traitement.php' method='post'>
                                                    <input type='hidden' name='user_id' value='" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "'>
                                                    <button type='submit' class='btn btn-warning'>Promouvoir</button>
                                                </form>
                                                <form action='./moderation/downgrade_traitement.php' method='post'>
                                                    <input type='hidden' name='user_id' value='" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "'>
                                                    <button type='submit' class='btn btn-danger'>Rétrograder</button>
                                                </form>";
                                            }                                            
        
                $liste_profils .= "</div>
                                </div>
                            </div>";
            }
            $liste_profils .= "</div>";
            echo $liste_profils;
            echo "<div class='mt-3'>";
            echo "<a href='../index.php' class='btn btn-secondary'>Retour</a>";
            echo "</div>";
        } else {
            echo "Aucun profil trouvé.";
        }        
        ?>
    </body>
</html>