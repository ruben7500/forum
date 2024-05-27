<?php
session_start();
require_once '../config.php';

if (isset($_POST['pseudo_email'], $_POST['password'])) {
    $pseudo_email = $_POST['pseudo_email'];
    $password = $_POST['password'];

    if (filter_var($pseudo_email, FILTER_VALIDATE_EMAIL)) {
        $query = 'SELECT pseudo, email, password FROM membre WHERE email = ?';
    } else {
        $query = 'SELECT pseudo, email, password FROM membre WHERE pseudo = ?';
    }

    $stmt = $bdd->prepare($query);
    $stmt->execute([$pseudo_email]);
    $user = $stmt->fetch();

    if ($user && hash_equals($user['password'], hash('sha256', $password))) {
        $_SESSION['userName'] = $user['pseudo'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $update = $bdd->prepare("UPDATE membre SET ip = ? WHERE pseudo = ?");
        $update->execute([$ip, $user['pseudo']]);
        header('Location: ../index.php');
        exit();
    } else {
        header('Location: connexion.php?login_err=invalid_credentials');
        exit();
    }
} else {
    header('Location: connexion.php?login_err=missing_fields');
    exit();
}
?>
