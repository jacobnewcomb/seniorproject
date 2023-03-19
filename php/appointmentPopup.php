<?php

require_once 'databaseConnection.php';
$instance = ConnectDb::getInstance();
$conn = $instance->getConnection();

$apt_id = $_POST['apt_id'];

$query = "SELECT * FROM appointments WHERE apt_id='$apt_id';";
$apt = mysqli_fetch_assoc(mysqli_query($conn, $query));

$query = "SELECT * 
    FROM (customer C join customerscar CC on C.cust_id = CC.cust_id) 
        join appointments A on CC.id = A.cust_car_id 
    WHERE cust_car_id = " . $apt['cust_car_id'];

$cust = mysqli_fetch_assoc(mysqli_query($conn, $query));
?>

<div class="popupBackground" onclick="unpop()"></div>
<div class="popupMessage">

    <h3>Name: <?= $cust['f_name'], " ", $cust['l_name'] ?></h3>
    Location: <input name="loc" type="text" value="<?= $apt['location'] ?>"><br>
    Start Date: <input name="start_date" type="datetime-local" value="<?= $apt['start_date'] ?>"><br>
    End Date: <input name="end_date" type="datetime-local" value="<?= $apt['start_date'] ?>"><br>
    Notes: <input name="notes" type="text" value="<?= $apt['notes'] ?>"><br>
    Labor Hours: <input name="labor_hours" type="number" value="<?= $apt['labor_hours'] ?>"><br>
    <button onclick="updateApt(<?= $apt['apt_id'] ?>)">Update</button>
    
</div>