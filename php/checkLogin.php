<?php
session_start();

$hostname     = "localhost";
$username     = "senior";
$password     = "project";
$database     = "seniorproject";
// Create connection
$conn = mysqli_connect($hostname, $username, $password, $database);
// Check connection
if (!$conn) {
    die("Unable to Connect database: " . mysqli_connect_error());
}

if(!isset($_SESSION["id"])){
    echo json_encode(false);
}
else{
    $query = "SELECT * FROM `users` WHERE MD5(CONCAT(username, password)) = '" . $_SESSION['id'] . "';";
    $exec = mysqli_query($conn, $query);
    mysqli_close($conn);
    if($exec->num_rows == 1){
        echo json_encode(true);
    }
    else{
        echo json_encode(false);
    }
}
?>