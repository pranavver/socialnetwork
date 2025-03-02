<?php $version=time()?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social network Login</title>
    <script src="./js/jquery-3.7.1.js"></script>
    <link rel="stylesheet" href="./css/login.css?v=<?=$version?>">
</head>
<body>
    <h3>Social Network Login</h3>
    <div class="form-container">
        <form action="login_process.php" method="post">

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required/>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required/>
            </div>

            <div class="form-group">
                <button type="submit" id="btn">Login</button>
                <label>Don't have account? <a href="register.php">Create Account</a></label>
            </div>

        </form>
    </div>
    <script src="./js/login.js?v=<?=$version?>"></script>
</body>
</html>