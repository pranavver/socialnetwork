<?php
session_start();
require './database/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'unauthorized', 'message' => 'User not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$response = ['status' => 'error'];

// Fetch the current profile picture to delete it later
$stmt = $conn->prepare("SELECT profile_pic FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($current_profile_pic);
$stmt->fetch();
$stmt->close();

// Handle profile picture upload
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
    $upload_dir = 'uploads/';
    $file_ext = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'svg'];

    if (in_array($file_ext, $allowed_ext)) {
        $unique_name = uniqid() . "." . $file_ext;
        $file_path = $upload_dir . $unique_name;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $file_path)) {
            // Delete the previous profile picture if it's not the default image
            if ($current_profile_pic && $current_profile_pic !== 'default-profile.png' && file_exists($upload_dir . $current_profile_pic)) {
                unlink($upload_dir . $current_profile_pic);
            }

            // Update profile_pic in users table
            $stmt = $conn->prepare("UPDATE users SET profile_pic = ?, updated_at = NOW() WHERE id = ?");
            $stmt->bind_param("si", $unique_name, $user_id);

            if ($stmt->execute()) {
                $response = ['status' => 'success', 'profile_pic' => $file_path];
            }
        }
    } else {
        $response = ['status' => 'invalid_file'];
    }
}

// Handle level update
if (isset($_POST['level'])) {
    $level = $_POST['level'];

    $stmt = $conn->prepare("UPDATE users SET level = ?, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("si", $level, $user_id);

    if ($stmt->execute()) {
        $response = ['status' => 'success', 'level' => $level];
    } else {
        $response = ['status' => 'update_failed'];
    }
}

echo json_encode($response);
exit;
?>
