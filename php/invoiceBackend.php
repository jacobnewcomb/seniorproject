<?php

require_once 'databaseConnection.php';
$instance = ConnectDb::getInstance();
$conn = $instance->getConnection();

$invoice_id = $_POST['invoice_id'];
if ($invoice_id == 0) {
?>
    <h3>Choose Invoice</h3>
    <?php
} else {

    $section = $_POST['section'];


    if ($section == "customer_info") :
        // get invoice
        $query = "SELECT * FROM invoices WHERE invoice_id = '$invoice_id';";
        $cust_id = mysqli_fetch_assoc(mysqli_query($conn, $query))['cust_id'];

        $query = "SELECT * FROM customer WHERE cust_id = '$cust_id';";
        $cust = mysqli_fetch_assoc(mysqli_query($conn, $query));
    ?>

        <div id="auto_input_grid">
            <div id="inv_id">
                <h3>
                    Invoice Num: <?= $invoice_id ?>
                    <h3>
            </div>
            <div id="fname">
                <?= $cust['f_name'] ?>
            </div>
            <div id="lname">
                <?= $cust['l_name'] ?>
            </div>
            <div id="notes">
                <label for="notes_area">Notes:</label><br>
                <textarea style="width: 100%;" id="notes_area"></textarea>
            </div>
        </div>

        <?php
    elseif ($section == "appointments") :
        // get apts that use this invoice num
        $query = "SELECT * FROM appointments WHERE invoice_id = '$invoice_id';";
        $apts = mysqli_query($conn, $query);

        $apt_num = 1;
        foreach ($apts as $apt) : ?>

            <div>
                <h4>Appointment <?= $apt_num ?></h4>
                <div>
                    Start Date: <?= $apt['start_date'] ?>
                </div>
                <div>
                    End date: <?= $apt['end_date'] ?>
                </div>
                <div>
                    Location: <?= $apt['location'] ?>
                </div>
                <div>
                    Add car info
                </div>
                <div>
                    Notes: <?= $apt['notes'] ?>
                </div>
                <div>
                    Labor hours
                    <input type="number" name="hours_apt_<?= $apt_num ?>">
                </div>
                <hr>
            </div>

<?php
            $apt_num += 1;
        endforeach;
    endif;
} ?>