<?php
session_start();
require_once '../config.php';
$name = isset($_SESSION['userName']) ? $_SESSION['userName'] : false;
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
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <form action="compte-edit_traitement.php" method="post" class="bg-light p-4 mt-5 rounded">
                            <h2 class="text-center mb-4">Modifier mon pseudo</h2>
                            <div class="form-group">
                                <input type="text" name="pseudo" class="form-control" placeholder="Modifier mon pseudo" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Modifier</button>
                            </div>
                        </form>
                        <form action="compte-edit_traitement.php" method="post" class="bg-light p-4 mt-5 rounded">
                            <h2 class="text-center mb-4">Modifier mon mot de passe</h2>
                            <div class="form-group">
                                <input type="password" name="password" class="form-control" placeholder="Modifier mon mot de passe" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Modifier</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row py-3 justify-content-center">
                    <a type="button" href="./all-profils.php" class="btn btn-outline-secondary">Voir tous les profils</a>
                </div>
                <div class="row py-3 justify-content-center">
                    <a type="button" href="./info-compte.php" class="btn btn-outline-secondary">Activité de votre compte</a>
                </div>
                <div class="row py-3 justify-content-center">
                    <a type="button" href="./deconnexion.php" class="btn btn-outline-secondary">Deconnexion</a>
                </div>
            </div>
        </body>
</html>
