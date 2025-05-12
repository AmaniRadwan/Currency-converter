<?php
require_once 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($user);
} else {
    http_response_code(404);
    echo json_encode(["error" => "User not found"]);
}
?>
