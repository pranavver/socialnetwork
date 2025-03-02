<?php
        session_start();
        if (!isset($_SESSION["user_id"]) && $_SESSION["email"])
        {
            header("Location: index.php");
            exit();
        }

        require_once './database/db_connect.php';
        // && $_FILES['image']['error'] === UPLOAD_ERR_OK
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $userId = $_SESSION['user_id'];
                $postDescription = $_POST['description'];
                // Handle post image upload
                $postImage = null;
                $folder=null;
                if (!empty($_FILES['image']['name'])) {
                    $postImage =  uniqid() . '-' . basename($_FILES['image']['name']);
                    $folder='./uploads/' . $postImage;
                    move_uploaded_file($_FILES['image']['tmp_name'], $folder);
                }

                // Insert post into the database
                $stmt = $conn->prepare("INSERT INTO posts (user_id, description) VALUES (?, ?)");
                $stmt->bind_param("is", $userId, $postDescription);

                if ($stmt->execute()) {
                    $postId = $stmt->insert_id;

                    if ($postImage) {
                        // Insert post image into the database
                        $stmtImg = $conn->prepare("INSERT INTO post_images (post_id, image_path) VALUES (?, ?)");
                        $stmtImg->bind_param("is", $postId, $postImage);
                        $stmtImg->execute();
                        $stmtImg->close();
                    }
                    echo json_encode(["status" => "success", "message" => "Post uploaded successfully."]);
                    exit();
                    }
                    else {
                    echo json_encode(["status" => "error", "message" => "Error: Failed to insert post into the database"]);
                    }
                } 
                catch (Exception $e) 
                {
                    if ($folder && file_exists($folder)) {
                    unlink($folder);  // Delete the uploaded file on error
                }
                echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
            }
        }
        else{
            echo json_encode(["status"=> "not upload","message"=> "required image is not uploaded"]);
        }

?>