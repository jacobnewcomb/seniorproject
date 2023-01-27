<?php

$hostname = 'localhost';
$username = "senior";
$password = "project";
$database = 'seniorproject';

$conn = mysqli_connect($hostname, $username, $password, $database);

if (!$conn) {
    die('Unable to connect database: ' . mysqli_connect_error());
}

# get data
$fname = $_POST['first_name'];
$lname = $_POST['last_name'];
$phone = $_POST['phone_num'];
# optionals
$addr = $_POST['address'];
$email = $_POST['email'];
$tup = "( '$fname', '$lname', '$phone', '$addr', '$email' )";

# check if info is valid

# check if this info already exists

# construct tuple

# insert
$query = 'INSERT INTO customer (f_name, l_name, phone, address, email) VALUES ' . $tup;
mysqli_query($conn, $query);