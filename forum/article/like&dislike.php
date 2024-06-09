<?php
session_start();
require_once '../config.php';
$name = isset($_SESSION['userName']) ? $_SESSION['userName'] : false;

$data = json_decode(file_get_contents("php://input"), true);
$user_id = $data['user_id'];
$article_id = intval($data['article_id']);
$vote_type = $data['vote_type'];

$sql = "DELETE FROM votes WHERE user_id = :user_id AND article_id = :article_id";
$stmt = $bdd->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
$stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
$stmt->execute();

$sql = "INSERT INTO votes (article_id, user_id, vote_type) VALUES (:article_id, :user_id, :vote_type)";
$stmt = $bdd->prepare($sql);
$stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
$stmt->bindParam(':vote_type', $vote_type, PDO::PARAM_STR);
$stmt->execute();

$sql = "SELECT 
            SUM(vote_type = 'like') AS likes, 
            SUM(vote_type = 'dislike') AS dislikes 
        FROM votes 
        WHERE article_id = :article_id";
$stmt = $bdd->prepare($sql);
$stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$response = [
    'likes' => $row['likes'],
    'dislikes' => $row['dislikes']
];

header('Content-Type: application/json');
echo json_encode($response);
?>
