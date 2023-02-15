<?php
class tags{
    public $name;
    public $location;
    public $notes;
}

require_once 'databaseConnection.php';
$instance = ConnectDb::getInstance();
$conn = $instance->getConnection();

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
    public $tags;
}
$events = array();

foreach($data as $row) {
    $e = new Event();
    $e->id = $row['apt_id'];
    $query = "SELECT * FROM customer WHERE cust_id = " . $row['cust_id'];
    $exec = mysqli_query($conn, $query);
    $custInfo = mysqli_fetch_assoc($exec);
    $e->text = $custInfo['f_name'] . " " . $custInfo['l_name'];
    $tags = new tags();
    $tags->name = $custInfo['f_name'] . " " . $custInfo['l_name'];
    $tags->location = $row['location'];
    $tags->notes = $row['notes'];
    $e->tags = $tags;
    $e->start = str_replace(" ","T",$row['start_date']);
    $e->end = str_replace(" ","T",$row['end_date']);
    $events[] = $e;
}

echo json_encode($events);
?>