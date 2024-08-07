
<?php
//session_start();
$servername = "localhost";
$username = "id21444793_root";
$password = "Rootdb$123";
$dbname = "id21444793_seproject";

//Creating connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

//Checking connection
if(!$conn){
    die("Connection failed: ".mysqli_connect_error());
}
//echo "Connection was successful";

/*
//Creating database called SEproject
$sql = "CREATE DATABASE IF NOT EXISTS SEproject";
if(mysqli_query($conn, $sql)){
    echo "Database created successfully";
} else{
    echo "Error creating database: ".mysqli_error($conn);
}
*/
//mysqli_close($conn);
?>