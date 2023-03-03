<?php

require_once 'databaseConnection.php';
$instance = ConnectDb::getInstance();
$conn = $instance->getConnection();

$item = $_POST['item'];


$query = "SELECT * FROM inventory WHERE name LIKE '%" . $item . "%';";

$results = mysqli_query($conn, $query);

if ($item != "" && mysqli_num_rows($results) > 0) :
    foreach ($results as $items) : ?>
        <tr>
            <td><button style="width: 100%; height: 1em; background-color: green"></td>
            <td style="text-align: center"><?= $items["name"]; ?></td>
            <td style="text-align: center">$<?= $items["price_per_unit"]; ?></td>
            <td><button>+</button></td>
            <td style="text-align: center"><?= $items["quantity"]; ?></td>
            <td><button>-</button></td>
            <td><button style="width: 100%; height: 1em; background-color: red"></button></td>
        </tr>
    <?php endforeach;
else : ?>
    <tr>
        <td colspan="5">No record found...</td>
    </tr>
<?php endif; ?>