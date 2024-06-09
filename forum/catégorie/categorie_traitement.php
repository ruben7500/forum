<?php
session_start();
require_once '../config.php';
$name = $_SESSION['userName'];

if (isset($_POST['nom']) && isset($_POST['description'])) {
    $nom = htmlspecialchars($_POST['nom']);
    $description = htmlspecialchars($_POST['description']);

    $check = $bdd->prepare('SELECT nom FROM categorie WHERE nom = ?');
    $check->execute(array($nom));
    $row = $check->rowCount();

    if ($row == 0) {
        $insert = $bdd->prepare('INSERT INTO categorie(nom, description) VALUES(:nom, :description)');
        $insert->execute(array(
            'nom' => $nom,
            'description' => $description
        ));
        header('Location: ../index.php?success');
        exit();
    } else {
        header('Location: categorie.php?error');
        exit();
    }
}
?>
