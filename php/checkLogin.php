<?php
session_start();

require_once 'databaseConnection.php';
$instance = ConnectDb::getInstance();
$conn = $instance->getConnection();

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