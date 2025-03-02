<?php

        session_start();
        if (!isset($_SESSION["user_id"]) && $_SESSION["email"])
        {
            header("Location: index.php");
            exit();
        }

        require './database/db_connect.php';
        
        try{
            // Get user details using session variable (user_id)
            $userId = $_SESSION['user_id'];

            $query = "SELECT name, email, profile_pic, level, created_at FROM users WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $stmt->store_result();  // Store the result in memory

            if ($stmt->num_rows > 0)
                {
                    $stmt->bind_result( $name, $email, $profilePic, $level, $createdAt); // Bind the result columns to variables
                    $stmt->fetch(); // Fetch the result
                }
            else
                {
                    echo "User not found.";
                    $conn->close();
                    exit();
                }
            
            // Fetch posts of  the user from database 
            $postQuery = "SELECT p.id AS post_id, p.description, p.created_at AS post_date, p.likes, p.dislikes, u.name AS user_name, u.profile_pic
                FROM posts p INNER JOIN users u ON p.user_id = u.id 
                WHERE p.user_id = ? ORDER BY p.created_at DESC";

                
                $postStmt = $conn->prepare($postQuery);
                $postStmt->bind_param("i", $userId);
                $postStmt->execute();
                $postStmt->store_result();  // Store the result in memory
            
            // Fetch posts details into an associative array
            $posts = [];    
            if ($postStmt->num_rows > 0) {
                $postStmt->bind_result($postId, $description, $postDate, $likesCount, $dislikesCount, $userName, $userProfilePic);
                while ($postStmt->fetch()) {
                    $posts[] = [
                        'post_id' => $postId,
                        'description' => $description,
                        'post_date' => $postDate,
                        'likes_count' => $likesCount,
                        'dislikes_count' => $dislikesCount,
                        'user_name' => $userName,
                        'user_profile_pic' => $userProfilePic
                    ];
                }
            }
            
            
        }
        catch (Exception $e)
            {
                echo $e->getMessage();
            }
        
        
        // $conn->close();
        ?>

