<?php $version=time()?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register social network</title>
    <script src="./js/jquery-3.7.1.js"></script>
    <link rel="stylesheet" href="./css/register.css?v=<?=$version?>"/>
</head>
<body>
    <h3 style="text-align:center;">Join Social Network</h3>
    <div class="form-container">
        <form action="register_process.php" method="post" enctype="multipart/form-data">

            <div class="form-group">
                <img src="./uploads/default-profile.png" accept="image/*" alt="Profile Pic" class="profile-pic"/>
                <input type="file" name="profile_pic" id="profile-pic-input"/>
                <label for="profile-pic-input" class>Upoad Profile Pic</label>
            </div>

            <div class="form-group">
                <label for="full-name">Full Name</label>
                <input type="text" id="full-name" name="name" required/>
            </div>

            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" min="1900-01-01" max="2015-12-31" required/>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required/>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required/>
                    <label id="suggession">Use A-Z, a-z, 0-9, !@#$%^&* in password</label>
                </div>

                <div class="form-group">
                    <label for="repassword">Re - Password</label>
                    <input type="password" name="repassword" id="repassword" required/>
                </div>
            </div>

            <div class="form-group">
                <input type="submit" value="Sign Up"/>
            </div>

        </form>
    </div>
    <script src="./js/register.js?v=<?=$version?>"></script>
</body>
</html>