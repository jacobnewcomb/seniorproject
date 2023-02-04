<?php
session_start();

require_once 'databaseConnection.php';
$instance = ConnectDb::getInstance();
$conn = $instance->getConnection();

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