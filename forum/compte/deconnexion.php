<?php
session_start();
require_once '../config.php';
$name = isset($_SESSION['userName']) ? $_SESSION['userName'] : false;

if (isset($_SESSION['userName'])) {
    $pof = $_SESSION['userName'];
    $ps = '';
    $update = $bdd->prepare("UPDATE membre SET ip = :ip WHERE pseudo = :pseudo");
    $update->execute([
        'ip' => $ps,
        'pseudo' => $pof
    ]);
    session_destroy();
}
header('Location: ../index.php');
exit();
?>