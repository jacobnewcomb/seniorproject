<?php
$hostname     = "localhost";
$username     = "senior";
$password     = "project";
$database     = "seniorproject";

$conn = mysqli_connect($hostname, $username, $password, $database);
if (!$conn) {
    die("Unable to Connect database: " . mysqli_connect_error());
}

$query = "SELECT * FROM appointments";
$exec = mysqli_query($conn, $query);
$data = array();
while($row = mysqli_fetch_assoc($exec))
{
    $data[] = $row;
}


class Event {
    public $id;
    public $text;
    public $start;
    public $end;
}
$events = array();

foreach($data as $row) {
    $e = new Event();
    $e->id = $row['apt_id'];
    $query = "SELECT f_name, l_name FROM customer WHERE cust_id = " . $row['cust_id'];
    $exec = mysqli_query($conn, $query);
    $custInfo = mysqli_fetch_assoc($exec);
    $e->text = $custInfo['f_name'] . " " . $custInfo['l_name'];
    $e->start = str_replace(" ","T",$row['start_date']);
    $e->end = str_replace(" ","T",$row['end_date']);
    $events[] = $e;
}

echo json_encode($events);
?>