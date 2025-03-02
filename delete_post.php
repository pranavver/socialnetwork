<?php


session_start();
require './database/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'unauthorized']);
    exit;
}

// Validate the request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_id'])) {
    $post_id = intval($_POST['post_id']);
    $user_id = $_SESSION['user_id'];

    // Check if the post belongs to the logged-in user
    $checkPostQuery = "SELECT * FROM posts WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($checkPostQuery);
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['status' => 'forbidden']);
        exit;
    }

    // Delete associated images
    $imgQuery = "SELECT image_path FROM post_images WHERE id = ?";
    $stmt = $conn->prepare($imgQuery);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $images = $stmt->get_result();

    while ($row = $images->fetch_assoc()) {
        $imagePath = './uploads/' . $row['image_path'];
        if (file_exists($imagePath)) {
            unlink($imagePath);  // Delete image file
        }
    }

    // Delete images from database
    $deleteImages = "DELETE FROM post_images WHERE post_id = ?";
    $stmt = $conn->prepare($deleteImages);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();

    // Delete the post
    $deletePost = "DELETE FROM posts WHERE id = ?";
    $stmt = $conn->prepare($deletePost);
    $stmt->bind_param("i", $post_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
} else {
    echo json_encode(['status' => 'invalid_request']);
}
?>





