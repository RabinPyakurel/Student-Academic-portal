<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="../assets/css/form.css">
    <link rel="stylesheet" href="../assets/css/terms.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <style>
        .forgot_password {
            padding-bottom: 1.5rem;
        }
    </style>
</head>

<body>
    <?php include '../layout/loader.htm' ?>
    <div id="loader-container" style="display: none;">
        <div class="loader">
            <img src="../assets/images/loging_in.gif" alt="Loading...">
        </div>
    </div>
    <form class="form" method="post">
    <div class="logo-container">
        <img src="../assets/images/final-logo.png" alt="Logo" class="form-logo">
    </div>
        <span class="close-btn redirect-index">&times;</span>
        <h1 class="text-center">Login Form</h1>
        <div class="input-group">
            <input type="text" name="username" id="username" placeholder="">
            <label for="username">Id card num/college email</label><br>
            <span class="error" id="usernameMsg"></span>
        </div>
        <div class="input-group">
            <input type="password" name="password" id="pass" placeholder="">
            <label for="pass">Password</label><br>
            <span class="toggle-eye" onclick="togglePassword('pass', this)">&#128065;</span>
            <span class="error" id="passMsg"></span>
        </div>
        <div class="forgot_password">
            <a href="reset_request.php" class="link" id="forgotpass">Forgot password?</a>
        </div>

        <div>
            <input type="submit" class="btn ml-auto" value="Login">
        </div>
        <div class="link text-center">
            Don't have an account? <a href="./sign-up.php"> Register here</a>
        </div>
    </form>
    <script src="../assets/js/loginValidation.js"></script>
    <script src="../assets/js/form.js"></script>
</body>

</html>