<?php
session_start();
require_once '../config.php';

if (isset($_POST['pseudo']) && isset($_POST['email']) && isset($_POST['password'])) {
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    $check = $bdd->prepare('SELECT pseudo, email, password FROM membre WHERE email = ?');
    $check->execute(array($email));
    $row = $check->rowCount();

    if ($row == 0) {
        $checkPseudo = $bdd->prepare('SELECT pseudo FROM membre WHERE pseudo = ?');
        $checkPseudo->execute(array($pseudo));
        $rowPseudo = $checkPseudo->rowCount();

        if ($rowPseudo == 0) {
            if (strlen($pseudo) <= 100) {
                if (strlen($email) <= 100) {
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $password = hash('sha256', $password);
                        $insert = $bdd->prepare('INSERT INTO membre(pseudo, password, email, role) VALUES(:pseudo, :password, :email, :role)');
                        $insert->execute(array(
                            'pseudo' => $pseudo,
                            'password' => $password,
                            'email' => $email,
                            'role' => "normal"
                        ));
                        header('Location: connexion.php?reg_err=success');
                        exit();
                    } else {
                        header('Location: inscription.php?reg_err=email');
                        exit();
                    }
                } else {
                    header('Location: inscription.php?reg_err=email_length');
                    exit();
                }
            } else {
                header('Location: inscription.php?reg_err=pseudo_length');
                exit();
            }
        } else {
            header('Location: inscription.php?reg_err=pseudo_taken');
            exit();
        }
    } else {
        header('Location: inscription.php?reg_err=already');
        exit();
    }
}
?>
