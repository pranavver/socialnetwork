<?php
session_start();
require './database/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $postId = $_POST['post_id'];
    $reaction = $_POST['reaction'];  // 'like' or 'dislike'

    if ($reaction === 'like') {
        $query = "UPDATE posts SET likes = likes + 1 WHERE id = ?";
    } elseif ($reaction === 'dislike') {
        $query = "UPDATE posts SET dislikes = dislikes + 1 WHERE id = ?";
    } else {
        echo json_encode(['error' => 'Invalid reaction']);
        exit();
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $postId);
    $stmt->execute();

    $countQuery = "SELECT likes, dislikes FROM posts WHERE id = ?";
    $countStmt = $conn->prepare($countQuery);
    $countStmt->bind_param("i", $postId);
    $countStmt->execute();
    $countStmt->bind_result($likes, $dislikes);
    $countStmt->fetch();

    echo json_encode(['likes' => $likes, 'dislikes' => $dislikes]);
} else {
    echo json_encode(['error' => 'Unauthorized']);
}
?>
