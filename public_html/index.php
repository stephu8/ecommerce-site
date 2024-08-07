<!DOCTYPE html>
<html lang="en">
    <?php
    session_start();
    include('connect.php');
    include('functions.php');
    
    if(isset($_POST['submit'])){
        $fname = $_POST['fname'];
        $email = $_POST['email'];
        $user_password = $_POST['user_password'];

        if(check_exists($conn, $email) === FALSE){
            $sql = "INSERT INTO users (firstname, email, user_password) VALUES ('$fname', '$email', '$user_password')";
            mysqli_query($conn, $sql);
                $message[] = "New user registered successfully";
                //header("Location: login.php");
                echo "<script> location.href='login.php'; </script>";
                exit;
        }else{
            //echo "Error: ".$sql ."<br>".mysqli_error($conn);
            //echo "User already exists, login instead";
            $message[] = "User already exists, login instead";
        
        }
    }

    //mysqli_close($conn);
    ?>
    <head>
    <meta charset="UTF-8">
    <title>Register</title>
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
            <form class="sign-up" action="index.php" method="post">
                <h3> Register </h3>
                <input type="text" name="fname" required placeholder="Enter First Name" class="box"> 
                <input type="email" name="email" required placeholder="Enter Email" class="box">
                <input type="password" name="user_password" required placeholder="Enter Password" class="box"> 
                <input type="submit" name="submit" value="Register" class="btn"> 
                <p>Already have an account? <a href="login.php">Login</a> </p>
            </form>
        </div>
   </body>
</html>