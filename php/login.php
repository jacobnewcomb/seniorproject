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

$username = $_POST['username'];
$password = $_POST['password'];

$query = "SELECT * FROM `users` WHERE username = '" . $username . "' AND password = '" . md5($password) . "';";
$exec = mysqli_query($conn, $query);
mysqli_close($conn);
if($exec->num_rows == 1){
    $_SESSION["id"] = md5($username . md5($password));
    echo json_encode(true);
}
else{
    echo json_encode(false);
}
?>