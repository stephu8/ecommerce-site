<?php
session_start();
include('connect.php');

if(isset($_POST['submit'])){
    $email = $_POST['email'];
    $user_password = $_POST['user_password'];
    $SELECT = "SELECT * FROM users where email = '$email' and user_password = '$user_password'";
    $result = mysqli_query($conn, $SELECT);
    if(mysqli_num_rows($result) == 1){
        //$_SESSION['loggedin'] = true;
        //session_start();
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $row['user_id'];
        //echo $_SESSION["user_id"];
        header("Location: shoppingPage.php");
        //echo "<script> location.href='shoppingPage.php'; </script>";
        //exit;
    }
    else{
        $message[]= "Username or password is incorrect.";
    }
}
//mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <!-- Bootstrap core CSS -->
        <link href="style.css" rel="stylesheet">
        <!-- Custom styles for this template -->
        <!-- <link href="signin.css" rel="stylesheet"> -->
    </head>
    <body>
        <?php
            if(isset($message)){
                foreach($message as $message){
                    echo '<div class="message" onclick="this.remove()">'.$message.'</div>';
                }
            }
        ?>
        <div class="form-container">
            <form class="sign-up" method="POST"> <!--action="login.php"-->
                <h3> Log in </h3>
                <!--Email:-->
                <input type="email" name="email"required placeholder="Enter Email" class="box">
                <!--Password:-->
                <input type="password" name="user_password" required placeholder="Enter Password" class="box">
                <input type="submit" name='submit' value="Log in" class="btn">
                
                <p>Don't have an account? <a href="index.php">Register</a> </p>
            </form>
        </div>
    </body>
</html>