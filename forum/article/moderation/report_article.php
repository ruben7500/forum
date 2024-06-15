<?php
session_start();
require_once '../../config.php';
$name = isset($_SESSION['userName']) ? $_SESSION['userName'] : false;

    $articleId = htmlspecialchars($_POST['article_id'], ENT_QUOTES, 'UTF-8');
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
            <div class="report-form">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <form action="./report_traitement.php" method="post" class="bg-light p-4 mt-5 rounded">
                                <h2 class="text-center mb-4">Report</h2>
                                <input type="hidden" name="article_id" value="<?php echo $articleId; ?>">
                                <div class="form-group">
                                    <input type="text" name="titre" class="form-control" placeholder="Raison du report" required="required" autocomplete="off">
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
                                    <button type="submit" class="btn btn-warning btn-block">Report</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </body>
</html>
