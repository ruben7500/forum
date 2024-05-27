<?php
session_start();
require_once '../config.php';
?>

<!DOCTYPE html>
    <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet" />
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
            <title>forum</title>
        </head>
        <body>
            <div class="login-form">
                <?php
                    if(isset($_GET['login_err']))
                    {
                        $err = htmlspecialchars($_GET['login_err']);

                        switch($err)
                        {
                            case 'password':
                            ?>
                                <div class="alert alert-danger">
                                    <strong>Erreur</strong> mot de passe incorrect
                                </div>
                            <?php
                            break;

                            case 'email':
                            ?>
                                <div class="alert alert-danger">
                                    <strong>Erreur</strong> email incorrect
                                </div>
                            <?php
                            break;

                            case 'already':
                            ?>
                                <div class="alert alert-danger">
                                    <strong>Erreur</strong> compte non existant
                                </div>
                            <?php
                            break;

                            case 'ban':
                            ?>
                                <div class="alert alert-danger">
                                    <strong>Erreur</strong> compte bannis
                                </div>
                            <?php
                            break;
                        }
                    }
                    ?>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <form action="connexion_traitement.php" method="post" class="bg-light p-4 mt-5 rounded">
                                <h2 class="text-center mb-4">Connexion</h2>
                                <div class="form-group">
                                    <input type="text" name="pseudo_email" class="form-control" placeholder="Pseudo ou Email" required="required" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control" placeholder="Mot de passe" required="required" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">Connexion</button>
                                </div>
                            </form>
                            <div class="text-center mt-3">
                                <a href="inscription.php">Pas encore de compte ? inscrivez-vous !</a>
                            </div>
                        </div>
                    </div>
                </div>


    <?php
    $po = "";
    $get = $bdd->prepare('SELECT COUNT(id) AS nbr FROM membre WHERE pseudo != ?');
        $get->execute(array($po));
    $data = $get->fetch();
    ?>

            </body>
</html>
