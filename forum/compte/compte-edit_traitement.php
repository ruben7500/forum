<?php
session_start();
require_once '../config.php';
$name = $_SESSION['userName'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pseudo'])) {
    $newPseudo = $_POST['pseudo'];

    if ($newPseudo != $_SESSION['userName']) {
        $checkPseudo = $bdd->prepare('SELECT pseudo FROM membre WHERE pseudo = ?');
        $checkPseudo->execute(array($newPseudo));
        $rowPseudo = $checkPseudo->rowCount();

        if ($rowPseudo == 0) {
            $updatePseudo = $bdd->prepare("UPDATE membre SET pseudo = :pseudo WHERE pseudo = :userName");
            $updatePseudo->execute(['pseudo' => $newPseudo, 'userName' => $_SESSION['userName']]);
            $_SESSION['userName'] = $newPseudo;
            header('Location: ../index.php?update=success');
            exit;
        } else {
            header('Location: ../index.php?update=failed_pseudo_taken');
            exit;
        }
    } else {
        header('Location: ../index.php?update=failed_same_pseudo');
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['password'])) {
    $newPassword = $_POST['password'];

    $hashedPassword = hash('sha256', $newPassword);

    $updatePassword = $bdd->prepare("UPDATE membre SET password = :password WHERE pseudo = :userName");
    $updatePassword->execute(['password' => $hashedPassword, 'userName' => $_SESSION['userName']]);
    header('Location: ../index.php?update=success');
    exit;
}
?>
