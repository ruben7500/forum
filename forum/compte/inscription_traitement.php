<?php
require_once '../config.php';
session_start();

if (isset($_POST['pseudo']) && isset($_POST['email']) && isset($_POST['password'])) {
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    function getIp() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

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
                        $insert = $bdd->prepare('INSERT INTO membre(pseudo, password, email) VALUES(:pseudo, :password, :email)');
                        $insert->execute(array(
                            'pseudo' => $pseudo,
                            'password' => $password,
                            'email' => $email
                        ));

                        $_SESSION['userName'] = $pseudo;
                        header('Location: ../index.php');
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