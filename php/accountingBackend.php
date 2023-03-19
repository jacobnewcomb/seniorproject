<?php

require_once 'databaseConnection.php';
$instance = ConnectDb::getInstance();
$conn = $instance->getConnection();

$query = "SELECT * FROM ledger;";
$ledger = mysqli_query($conn, $query);

foreach($ledger as $entry):

    $expense = $entry['quantity'] * $entry['expense_per_unit'];
    $item_id = $entry['item_id'];
    $invoice_id = $entry['invoice_id'];
    if($entry['withdraw'] == 0):
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
        <td><?= $item_id != 0 ? $item_id : ""?></td>
        <td><?= $invoice_id != 0 ? $invoice_id : ""?></td>
        <td><?= $entry['note']?></td>
    </tr>
<?php
endforeach;
