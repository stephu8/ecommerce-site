<?php
    function check_login($con){
        if(isset($_SESSION['user_id'])){
            $id = $_SESSION['user_id'];
            $query = "select * from users where user_id = '$id' limit 1";
            $result = mysqli_query($con, $query);
            if($result = mysqli_num_rows($result) > 0){
                $user_data = mysqli_fetch_assoc($result);
                return $user_data;
            }
        }
    }
    function check_exists($con, $email){
        #check if email exists in database
        $query = "select * from users where email = '$email' limit 1";
        $result = mysqli_query($con, $query);
        if($result = mysqli_num_rows($result) > 0){
            return TRUE;
        } else {
            return FALSE;
        }
    }
?>