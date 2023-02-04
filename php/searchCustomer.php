<?php

require_once 'databaseConnection.php';
$instance = ConnectDb::getInstance();
$conn = $instance->getConnection();

$fname = $_POST['fname'];
$lname = $_POST['lname'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$address = $_POST['address'];

$query = "select * from customer"; # where concat(customerId,fname,lname,phone,email,city,zip) like '%$search%'";
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