<?php
session_start();
require_once '../../config.php';

    $commentaireId = htmlspecialchars($_POST['commentaire_id'], ENT_QUOTES, 'UTF-8');

    $query = $bdd->prepare('DELETE FROM commentaires WHERE id = ?');
    $query->execute([$commentaireId]);

    header('Location: ../../index.php?reg_err=success');
    exit();
?>
