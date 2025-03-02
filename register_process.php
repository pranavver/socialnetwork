<?php
require './database/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try{
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $dob = $_POST['dob'];
        $profilePic = "default-profile.png";
        $folder=null;

        if (!empty($_FILES['profile_pic']['name'])) {
            $profilePic = uniqid() . '-' . basename($_FILES['profile_pic']['name']);
            $folder='./uploads/'. $profilePic;
            move_uploaded_file($_FILES['profile_pic']['tmp_name'], $folder);
        }

        $stmt = $conn->prepare("INSERT INTO users (name, email, password, dob, profile_pic) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $password, $dob, $profilePic);

        if ($stmt->execute()) {
            header("Location: index.php");
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
    } 
    catch (Exception $e) {
        unlink($folder);
        echo "Error occured :". $e->getMessage();
    }
}
?>
