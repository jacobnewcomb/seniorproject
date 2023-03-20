<?php

require_once 'databaseConnection.php';
$instance = ConnectDb::getInstance();
$conn = $instance->getConnection();

$query = "SELECT * FROM ledger;";
$ledger = mysqli_query($conn, $query);

foreach($ledger as $entry):

    $expense = $entry['quantity'] * $entry['expense_per_unit'];

    $item_id = $entry['item_id'];
    if($item_id != 0) {
        $query = "SELECT name FROM inventory WHERE item_id='$item_id';";
        $item_name = mysqli_fetch_assoc(mysqli_query($conn, $query))['name'];
    } else {
        $item_name = "";
    }

    $invoice_id = $entry['invoice_id'];
    if($entry['withdraw'] == 1):
?>
        <tr>
            <td style="color: green;"><?= $expense?></td>
            <td></td>
<?php
    else:
?>
        <tr>
            <td></td>
            <td style="color: red;">-<?= $expense?></td>
<?php
    endif;
?>
            <td><?= $item_name?></td>
<?php
    if ($invoice_id != 0) :
?>
        <td><button onclick="window.location.href=location.protocol+'//'+location.host+'/html/invoice.html?invoice_id=' + <?= $invoice_id ?>"><?= $invoice_id ?></button></td>
<?php
    else:
?>
        <td></td>
<?php
    endif;
?>
        <td><?= $entry['note']?></td>
    </tr>
<?php
endforeach;
