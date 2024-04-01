<?php
session_start();
include('includes/config.php');


if(isset($_SESSION['user']) && ($_SESSION['user']['type'] == 1 || $_SESSION['user']['type'] == 2)){
    header('location: dashboard/admin.php');
    exit();
}

if(isset($_SESSION['user']) && ($_SESSION['user']['type'] != 1 || $_SESSION['user']['type'] != 2)){
    header('location: sales/point-of-sales.php');
    exit();
}


// Initialize variables for error messages
$usernameError = $passwordError = $captchaError = '';

if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['g-recaptcha-response'])){
    // Check if username and password are provided
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(empty($username)) {
        $usernameError = "Please enter your username.";
    }
    if(empty($password)) {
        $passwordError = "Please enter your password.";
    }

    // Proceed with CAPTCHA verification only if username and password are provided
    if(!empty($username) && !empty($password)) {
        // Verify reCAPTCHA response
        $recaptchaResponse = $_POST['g-recaptcha-response'];
        $secretKey = '6Ld3imEpAAAAAAI3eqi0ttRg4dBtaoBG7jKBJ9I-'; // Replace with your secret key
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => $secretKey,
            'response' => $recaptchaResponse
        ];
        $options = [
            'http' => [
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        $context = stream_context_create($options);
        $verify = file_get_contents($url, false, $context);
        if ($verify !== false) {
            $captcha_success = json_decode($verify);
            if ($captcha_success->success) {
                // reCAPTCHA verification successful, proceed with user authentication
                $hashedPassword = md5($password);
                $sql = "SELECT * FROM tbl_user WHERE username = '$username' AND password = '$hashedPassword'";
                $result = mysqli_query($conn, $sql);

                if($result){
                    if(mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                            $_SESSION['user'] = array(
                                'user_id' => $row['id'],
                                'username' => $row['username'],
                                'type' => $row['usertype'],
                            );

                            if($_SESSION['user']['type'] == 1){
                                header('location: dashboard/admin.php');
                                exit(); // Add exit to stop further execution
                            } else if ($_SESSION['user']['type'] == 2){
                                header('location: dashboard/admin.php');
                                exit(); // Add exit to stop further execution
                            } else {
                                header('location: sales/point-of-sales.php');
                                exit(); // Add exit to stop further execution
                            }
                        }
                    } else {
                        echo '<script type="text/javascript">';
        echo 'alert("Wrong Username or Password");';
        echo '</script>';
                    }
                } else {
                    echo "Error executing query: " . mysqli_error($conn);
                }
            } else {
                // reCAPTCHA verification failed
                $captchaError = "Please complete the CAPTCHA.";
            }
        } else {
            // Error handling if file_get_contents fails
            echo "Failed to verify CAPTCHA. Please try again later.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GraphicsPOS | Login</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">

    <!-- Background styles -->
    <style>
        body {
            background-image: url('1111.png');
            background-size: cover;
            background-position: center;
            height: 100vh;
            margin: 0;
        }
    </style>
    <!-- reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-danger">
        <div class="card-header text-center">
            <a href="" class="h1"><b>Graphics</b> INC.</a>
        </div>
        <div class="card-body">
            <form action="" method="post">
                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <?php if(!empty($usernameError)) { echo "<div class='alert alert-danger'>$usernameError</div>"; } ?>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" id="password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span id="togglePassword" onclick="togglePasswordVisibility()">
                                <i class="far fa-eye"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <?php if(!empty($passwordError)) { echo "<div class='alert alert-danger'>$passwordError</div>"; } ?>
                <!-- reCAPTCHA -->
                <div class="g-recaptcha" data-sitekey="6Ld3imEpAAAAACg5z4ye9Nn2QPn8l96-Meargn9L"></div> <!-- Replace with your site key -->
                <?php if(!empty($captchaError)) { echo "<div class='alert alert-danger'>$captchaError</div>"; } ?>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-success btn-block">Sign In</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="assets/plugins/jquery/jquery.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="assets/dist/js/adminlte.min.js"></script>

<!-- Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js" integrity="sha512-V5/o8mDgLrCIZmsK3Xm8LSwlrMvQ6oUGdWSdDWPgRL6uR+8lb7qk4xXjnhuSFuQRDL8F6I4TVB4l6zL3Gr+hDg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    function togglePasswordVisibility() {
        var passwordField = document.getElementById("password");
        var toggleIcon = document.getElementById("togglePassword");
        
        if (passwordField.type === "password") {
            passwordField.type = "text";
            toggleIcon.innerHTML = '<i class="far fa-eye-slash"></i>';
        } else {
            passwordField.type = "password";
            toggleIcon.innerHTML = '<i class="far fa-eye"></i>';
        }
    }
</script>
</body>
</html>
