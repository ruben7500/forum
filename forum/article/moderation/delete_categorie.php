<?php
session_start();
require_once '../../config.php';

    $categorieId = htmlspecialchars($_POST['categorie_id'], ENT_QUOTES, 'UTF-8');

    $query = $bdd->prepare('DELETE FROM categorie WHERE id = ?');
    $query->execute([$categorieId]);

    header('Location: ../../index.php?reg_err=success');
    exit();
?>
