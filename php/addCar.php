<?php
    $hostname = 'localhost';
    $username = "senior";
    $password = "project";
    $database = 'seniorproject';
    
    $conn = mysqli_connect($hostname, $username, $password, $database);
    
    if (!$conn) {
        die('Unable to connect database: ' . mysqli_connect_error());
    }
    
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $refrigerent = $_POST['refrigerent'];
    if(ISSET($_POST['cust_id'])) $cust_id = $_POST['cust_id'];

    $query = "INSERT INTO car (Make, Model, Year, RefrigerentCapacity) VALUES ( '" . $make ."', '" . $model . "', '" . $year . "', '" . $refrigerent . "')";
    $exec = mysqli_query($conn, $query);
    $query = "SELECT id FROM car WHERE Make = '" . $make . "' AND Model = '" . $model . "' AND Year = " . $year;
    $exec = mysqli_query($conn, $query);
    $car_id = mysqli_fetch_assoc($exec)["id"];
    $query = "INSERT INTO customerscar (cust_id, car_id) VALUES (" . $cust_id . ", " . $car_id . ")";
    $exec = mysqli_query($conn, $query);
    $query = "SELECT id FROM customerscar WHERE cust_id = " . $cust_id . " AND car_id = " . $car_id;
    $exec = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($exec);
    mysqli_close($conn);
    if($data != NULL) echo $data["id"];
    else echo null;

?>