<?php
require './database/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) 
            {
                session_start();
                // $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $email;
                header("Location: home.php");
                echo "<script>alert('login Successfull')</script>";
            } 
        else 
            {
                echo "<script>alert('Invalid password.'); window.location.href='./index.php';</script>";
                exit();
            }
    } 
    else 
        {
            echo "<script>alert('User not found. Please register.'); window.location.href='./index.php';</script>";
            exit();
        }
    $stmt->close();
}
?>

