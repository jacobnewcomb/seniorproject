<?php
session_start();

require_once 'databaseConnection.php';
$instance = ConnectDb::getInstance();
$conn = $instance->getConnection();

if(!isset($_SESSION["id"])){
    echo false;
}
else{
    $query = "UPDATE `users` SET `flat_rate`=" . $_POST["fr"] . ",`tax_rate`=" . $_POST["tr"] . " WHERE MD5(CONCAT(username, password)) = '" . $_SESSION["id"] . "';";
    $exec = mysqli_query($conn, $query);
    echo true;
}
?>