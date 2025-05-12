<?php
require_once 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(["error" => "Not logged in"]);
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT first_name, last_name FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo json_encode($user);
} else {
    http_response_code(404);
    echo json_encode(["error" => "User not found"]);
}
?>