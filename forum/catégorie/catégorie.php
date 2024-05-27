<?php
ini_set('display_errors','off');
session_start();
require_once '../config.php';
$name = $_SESSION['userName'];
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
                            <form action="catégorie_traitement.php" method="post" class="bg-light p-4 mt-5 rounded">
                                <h2 class="text-center mb-4">Créer catégorie</h2>
                                <div class="form-group">
                                    <input type="text" name="nom" class="form-control" placeholder="Nom" required="required" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="description" class="form-control" placeholder="Description" required="required" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">Créer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        </body>
</html>
