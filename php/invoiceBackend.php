<?php

require_once 'databaseConnection.php';
$instance = ConnectDb::getInstance();
$conn = $instance->getConnection();

$section = $_POST['section'];

if ($section == "invoices") :
    // query invoices, structure table
    $query = "SELECT * FROM invoices;";
    $invoices = mysqli_query($conn, $query);

    foreach ($invoices as $invoice) :
            $invoice_id = $invoice['invoice_id'];

            $cust_id = $invoice['cust_id'];
            $query = "SELECT * FROM customer WHERE cust_id = '$cust_id';";
            $cust = mysqli_fetch_assoc(mysqli_query($conn, $query));

            $apt_id = $invoice['root_apt_id'];
            $query = "SELECT * FROM appointments WHERE apt_id = '$apt_id';";
            $apt = mysqli_fetch_assoc(mysqli_query($conn, $query));
?>
        <tr onclick="window.location.reload({'invoice_id': <?= $invoice_id ?>})">
            <td><?= $invoice_id?></td>
            <td><?= $cust['f_name']?></td>
            <td><?= $cust['l_name']?></td>
            <td><?= $apt['start_date']?></td>
        </tr>
    <?php
    endforeach;

else :
    $invoice_id = $_POST['invoice_id'];

    if ($section == "customer_info") :

        // get invoice
        $query = "SELECT * FROM invoices WHERE invoice_id = '$invoice_id';";
        $cust_id = mysqli_fetch_assoc(mysqli_query($conn, $query))['cust_id'];

        $query = "SELECT * FROM customer WHERE cust_id = '$cust_id';";
        $cust = mysqli_fetch_assoc(mysqli_query($conn, $query));
    ?>

        <div id="customer_grid">
            <div id="inv_id">
                <h3>
                    Invoice Num: <?= $invoice_id ?>
                </h3>
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
        foreach ($apts as $apt) : 
            $car_id = $apt['cust_car_id'];
            $query = "SELECT * FROM car WHERE id='$car_id';";
            $car = mysqli_fetch_assoc(mysqli_query($conn, $query));
            ?>

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
                    Car: <?= $car['Make'], " ", $car['Model'], ", ", $car['Year']?>
                </div>
                <div>
                    Notes: <?= $apt['notes'] ?>
                </div>
                <div>
                    Labor hours
                    <input type="number" value=<?= $apt['labor_hours']?> oninput="calc_labor_hours(this.value, <?= $apt_num?>)">
                </div>
                <hr>
            </div>

<?php
            $apt_num += 1;
        endforeach;
    endif;
endif;
?>