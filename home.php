<?php $version= time();
  require './database/db_connect.php';
  require 'fetch_homepage.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="./css/home.css?v=<?=$version?>"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="./js/jquery-3.7.1.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
  </head>
<body>
<span><i class="fa-solid fa-circle-up slide-up"></i></span>

  <div class="wrapper">
      <h3>Social Network</h3>
      <div id="message"></div>
    <div class="container">

      <div class="profile-info">
        <!-- <form id="profile_img" action="update_profile.php" method="POST" enctype="multipart/form-data"> -->
              <div class="profile">
                <img src="./uploads/<?= htmlspecialchars($profilePic) ?>" alt="Profile Pic" class="profile_pic" accept="image/*">
                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*" style="display: none;">
                  <!-- <i class="icon profile">&#9998;</i>  -->
                  <i class="fas fa-pen icon profile"></i>      
              </div>
              <h3><?=htmlspecialchars($name);?></h3>
              <p class="email"><?=htmlspecialchars($email);?></p>
              <div class="data">
                <input type="text" value="<?= htmlspecialchars($level ?? 'Intermediate') ?>" name="level" readonly disabled>
                <span class="edit-icon">
                <i class="fas fa-pen icon"></i>
                  <!-- <i class="icon">&#9998;</i> -->
                </span>
              </div>
        <!-- </form> -->
        <button class="share">Share Profile</button>
        <a href="logout.php" class="logout-icon">
        <i class="fa" >Logout &#xf08b;</i>
        </a>
      </div>

      
      <div class="posts">
        <div class="create-post">
          <div class="content-row">
              <lable id="add-post">Add Post</lable>
          </div>

            <div class="edit">
              
              <form id="postForm" method="POST" enctype="multipart/form-data">  <!-- no need to give action attribute because handle through AJAX -->
              <textarea class="content" name="description" rows="3" required></textarea>
              <img class="post-image upload-img"/>
              <i class="material-icons off"  style="display:none">&#xe888;</i>
            </div> 

            <div class="content-row">
              <button class="post-button">Post</button>
              <div class="img-add">
                <label for="img-1" class="add-image"><i class="material-icons add-image" id="img">&#xe3f4;</i>  Add Image</label>
                <div id="message" style="display:none"></div>
                <input type="file" id="img-1" name="image" accept="image/*" name="post_image" style="display:none"/>
              </div>
            </div> 
            </form>     
        </div>

      <!-- Loop through the posts and display them -->
      <?php foreach ($posts as $post): ?>
        <section class="post">
          <div class="content-row">
            <img src="./uploads/<?=$post['user_profile_pic']; ?>" alt="Profile Pic" class="profile-pic small">
            <div class="post-content">
              <p><?=htmlspecialchars($post['description']); ?></p>
            </div>
            <i class="close" data-post-id="<?=$post['post_id']?>">&#10006;</i>
          </div>
          <p class="post-meta">Posted on: <?=date('d M Y', strtotime($post['post_date']))?></p>

          <!-- Fetch and display post images -->
          <?php
          $imageQuery = "SELECT * FROM post_images WHERE post_id = ?";
          $stmt = $conn->prepare($imageQuery);
          $stmt->bind_param('i', $post['post_id']);
          $stmt->execute();
          $imagesResult = $stmt->get_result();
          ?>
          <?php if ($imagesResult->num_rows > 0): ?>
                <div class="post-images">
                    <?php while ($image = $imagesResult->fetch_assoc()): ?>
                        <img src="./uploads/<?=$image['image_path']; ?>" alt="Post Image" class="post-image">
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
          
          <div class="content-row">
            <label class="material-icons like" for="like" onclick="like_update(<?=$post['post_id']?>)" >&#xe8dc;</label>
            <p>Like</p>
            <p class="like"><span id="like_loop_<?=$post['post_id']?>"><?=$post['likes_count']?></span></p>&nbsp;&nbsp;&nbsp;&nbsp;
            
            <label class="material-icons dislike" for="dislike" onclick="dislike_update(<?=$post['post_id']?>)">&#xe8db;</label>
            <p>Dislike</p>
            <p class="dislike"><span id="dislike_loop_<?=$post['post_id']?>"><?=$post['dislikes_count']?></span></p>
          </div> 
        
        </section>
      <?php endforeach; ?>

      </div>
    </div>
  </div>
  <script src="./js/home.js?v=<?=$version?>"></script>
</body>
</html>

