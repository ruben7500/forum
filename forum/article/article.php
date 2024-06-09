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
                            <form action="article_traitement.php" method="post" enctype="multipart/form-data" class="bg-light p-4 mt-5 rounded">
                                <h2 class="text-center mb-4">Publier un article</h2>
                                <div class="form-group">
                                    <input type="text" name="titre" class="form-control" placeholder="Titre" required="required" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <textarea name="contenu" id="contenu" class="form-control" placeholder="Contenu" required="required" autocomplete="off"></textarea>
                                </div>
                                <script>
                                    var textarea = document.getElementById("contenu");
                                    textarea.addEventListener("input", function() {
                                        this.style.height = "auto";
                                        this.style.height = (this.scrollHeight) + "px";
                                    });
                                </script>
                                <div class="form-group">
                                    <div class="custom-file">
                                        <input type="file" name="img" class="custom-file-input" id="img" required="required" autocomplete="off" accept="image/jpeg, image/png, image/gif">
                                        <label class="custom-file-label" for="img">Choisir un fichier</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="categorie">categorie :</label>
                                    <select name="categorie">
                                        <option value="">--Choisis une categorie--</option>
                                        <?php
                                        $sql = "SELECT nom FROM categorie";
                                        $result = $bdd->query($sql);
                                        if ($result->rowCount() > 0) {
                                            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($rows as $row) {
                                                echo "<option value='" . $row['nom'] . "'>" . $row['nom'] . "</option>";
                                            }
                                        } else {
                                            echo "<option value=''>Aucune categorie trouvée</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">Publier</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        </body>
</html>
