<?php
session_start();
require_once '../../config.php';

if(isset($_POST['user_id'])) {
    $userId = htmlspecialchars($_POST['user_id'], ENT_QUOTES, 'UTF-8');

    $query = $bdd->prepare('SELECT * FROM membre WHERE id = ?');
    $query->execute([$userId]);
    $role = $query->fetch(PDO::FETCH_ASSOC);

    if ($role['role'] == 'normal'){
        $query = $bdd->prepare('UPDATE membre SET role = "moderateur" WHERE id = ?');
        $query->execute([$userId]);
    } else if ($role['role'] == 'moderateur'){
        $query = $bdd->prepare('UPDATE membre SET role = "administrateur" WHERE id = ?');
        $query->execute([$userId]);
    } else {
        header('Location: ../all-profils.php?reg_err=invalid_role');
        exit();
    }

    header('Location: ../all-profils.php?reg_err=success');
    exit();
} else {
    header('Location: ../all-profils.php?reg_err=missing_user_id');
    exit();
}
?>
