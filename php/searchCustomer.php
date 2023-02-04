<?php

require_once 'databaseConnection.php';
$instance = ConnectDb::getInstance();
$conn = $instance->getConnection();

$fname = $_POST['fname'];
$lname = $_POST['lname'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$address = $_POST['address'];

$sel = array();
$tup = array();

if(!empty($fname)) {
    $sel[] = "f_name";
    $tup[] = "'" . $fname . "'";
}

if(!empty($lname)) {
    $sel[] = "l_name";
    $tup[] = "'" . $lname . "'";
}

if(!empty($phone)) {
    $sel[] = "phone";
    $tup[] = "'" . $phone . "'";
}

if(!empty($email)) {
    $sel[] = "email";
    $tup[] = "'" . $email . "'";
}

if(!empty($address)) {
    $sel[] = "address";
    $tup[] = "'" . $address . "'";
}

$sel = "(" . implode(", ", $sel) . ")";
$tup = "(" . implode(", ", $tup) . ")";

$query = "select * from customer where " . $sel . " = " . $tup;
if ($tup == "()") {
    $query = "select * from customer";
}

$results = mysqli_query($conn, $query);


if (mysqli_num_rows($results) > 0) :
    foreach ($results as $items) : ?>
        <tr onclick="window.location='finalizeSale.php'">
            <td><?= $items['f_name']; ?></td>
            <td><?= $items['l_name']; ?></td>
            <td><?= $items['phone']; ?></td>
            <td><?= $items['email']; ?></td>
            <td><?= $items['address']; ?></td>
        </tr>
    <?php endforeach;
else : ?>
    <tr>
        <td colspan="5">No record found...</td>
    </tr>
<?php endif; ?>