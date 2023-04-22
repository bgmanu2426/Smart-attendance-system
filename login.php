<?php
//Start the session
session_start();
if (isset($_SESSION['userNumber'])) {
    header("location: logs.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Connecting to the databse
    require "./db/db_connect.php";

    //Variables to store and match the details from login form
    $user_number = $_POST["user_number"];
    $user_password = $_POST["user_pass"];

    if (isset($_POST['admin_user'])) {
        //Check the email and password entered by user is correct or not
        $sql = "SELECT * FROM `admin-login` WHERE admin_uid='$user_number'";
        $result = mysqli_query($connection, $sql);
        $num = mysqli_num_rows($result);
        $fetch = mysqli_fetch_assoc($result);
        // password_verify($user_password, $fetch['admin_pass']) //use it after hasing password 
        if (($num == 1) && $fetch['admin_pass'] == $user_password) {
            $_SESSION['userNumber'] = $user_number;
            $_SESSION['user'] = true;
            $_SESSION['admin'] = true;
            header("location: logs.php");
        } else {
            echo 'error';
        }
    } else {
        //Check the email and password entered by user is correct or not
        $sql = "SELECT * FROM `users-login` WHERE user_number='$user_number'";
        $result = mysqli_query($connection, $sql);
        $num = mysqli_num_rows($result);
        $fetch = mysqli_fetch_assoc($result);
        // password_verify($user_password, $fetch['user_pass']) use it after hasing password 
        if (($num == 1) && $fetch['user_pass'] == $user_password) {
            $_SESSION['userNumber'] = $user_number;
            $_SESSION['user'] = true;
            $_SESSION['admin'] = false;
            header("location: logs.php");
        } else {
            echo 'error';
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./css/style.css">
    <!-- Bootstrap CSS  -->
    <link rel="stylesheet" href="./node_modules/bootstrap/dist/css/bootstrap.min.css">
    <!-- Bootstrap JS -->
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <title>Login</title>
</head>

<body>
    <!-- Navbar included -->
    <?php include './apps/navbar.php'; ?>

    <!-- Login form -->
    <div class="container col-md-6 my-5" style="min-height: 66.5vh;">
        <h3 class="text-center m-4">Login **title**</h3>
        <form action="./login.php" method="POST">
            <div class="form-floating mb-3">
                <input type="text" class="form-control my-4" id="user_number" name="user_number" placeholder="name@example.com">
                <label for="user_number">Enter Number</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control my-4" id="user_pass" name="user_pass" placeholder="Password">
                <label for="user_pass">Enter Password</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" name="admin_user" id="admin_user">
                <label class="form-check-label" for="admin_user">
                    Admin
                </label>
            </div>
            <button type="submit" id='sub' class="btn btn-primary bg-gradient my-3">Login</button>
        </form>
    </div>

    <?php
    include './apps/footer.php';
    ?>
    <script>
        // To add active class to the navbar
        const addClassActive = document.getElementById('login-users-tab');
        addClassActive.classList.add('active');

        // To fix the re-submission error on reloading the webpage
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>

</html>