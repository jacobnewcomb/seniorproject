<?php

require_once 'databaseConnection.php';
$instance = ConnectDb::getInstance();
$conn = $instance->getConnection();

$section = $_POST['section'];
switch ($section) {
    case "invoice_section":
        invoice_section($conn);
        break;
    case "invoice_table":
        invoice_table($conn);
        break;
    case "update_apt":
        update_apt($conn);
        break;
    case "new_apt":
        new_apt($conn);
        break;
    case "delete_apt":
        delete_apt($conn);
        break;
}

function invoice_section($conn)
{
    $invoice_id = $_POST['invoice_id'];

    // customer info querys
    $query = "SELECT * FROM invoices WHERE invoice_id = '$invoice_id';";
    $invoice = mysqli_fetch_assoc(mysqli_query($conn, $query));

    $cust_id = $invoice['cust_id'];

    $query = "SELECT * FROM customer WHERE cust_id = '$cust_id';";
    $cust = mysqli_fetch_assoc(mysqli_query($conn, $query));

    $apt_id = $invoice['root_apt_id'];
    $query = "SELECT * FROM appointments WHERE apt_id='$apt_id';";
    $apt = mysqli_fetch_assoc(mysqli_query($conn, $query));

    $car_id = $apt['cust_car_id'];
    $query = "SELECT * FROM car WHERE id='$car_id';";
    $car = mysqli_fetch_assoc(mysqli_query($conn, $query));
    $car_id = $car['id'];

    // appointments querys
    $query = "SELECT * FROM appointments WHERE invoice_id = '$invoice_id';";
    $apts = mysqli_query($conn, $query);

    // summary querys
    $query = "SELECT sum(labor_hours) as total FROM appointments WHERE invoice_id='$invoice_id';";
    $total = mysqli_fetch_assoc(mysqli_query($conn, $query))['total'];

    $query = "UPDATE `invoices`
            SET `labor_hours` = '$total'
            WHERE invoice_id = '$invoice_id';";
    mysqli_query($conn, $query);

    $query = "SELECT flat_rate, tax_rate FROM users WHERE username='Uncle';";
    $user = mysqli_fetch_assoc(mysqli_query($conn, $query));
    $flat_rate = $user['flat_rate'];
    $tax_rate = $user['tax_rate'];

    $labor_charge = $total * $flat_rate;

    $combined_charge = $labor_charge; // + $inventory_charge
    $tax = $combined_charge * $tax_rate;

    $final_charge = $tax + $combined_charge;

    // put final charge in ledger
    // query ledger where invoice id = this
    $query = "SELECT * FROM ledger WHERE invoice_id='$invoice_id';";
    $ledger = mysqli_fetch_assoc(mysqli_query($conn, $query));

    // if no ledger exists, create
    if($ledger == null) {
        $query = "INSERT INTO `ledger` (`invoice_id`) 
            VALUES ('$invoice_id');";
        mysqli_query($conn, $query);

        $query = "SELECT * FROM ledger WHERE invoice_id='$invoice_id';";
        $ledger = mysqli_fetch_assoc(mysqli_query($conn, $query));
    }
    
    // add quantity, make it a deposit, add invoice id
    $quantity = $apt['labor_hours'];
    $ledger_id = $ledger['ledger_id'];
    $query = "UPDATE ledger
        SET quantity = '$quantity',
            expense_per_unit = '$flat_rate',
            invoice_id = '$invoice_id',
            withdraw = 0
        WHERE ledger_id='$ledger_id';";
    mysqli_query($conn, $query);
?>

    <!-- customer info section -->
    <section>
        <div id="customer">
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
                <div>
                    <?= $car['Make'] ?>
                </div>
                <div>
                    <?= $car['Model'] ?>
                </div>
                <div>
                    <?= $car['Year'] ?>
                </div>
            </div>
        </div>
    </section>

    <!-- appointments section -->
    <section>
        <div id="appointments">
            <?php
            $apt_num = 1;
            foreach ($apts as $apt) :
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
                        Notes: <?= $apt['notes'] ?>
                    </div>
                    <div>
                        Labor hours: <?= $apt['labor_hours'] ?>
                    </div>
                    <button class="no_print" onclick="popup(<?= $apt['apt_id'] ?>)">Edit</button>
                    <hr>
                </div>
            <?php
                $apt_num += 1;
            endforeach;
            ?>
        </div>
    </section>

    <!-- summary section -->
    <section>
        <div id="summary">
            <p>Total Labor Charge: $<?= $labor_charge ?></p>
            <p>Tax: $<?= $tax ?></p>
            <p>Final Charge: $<?= $final_charge ?></p>
        </div>
    </section>

    <!-- bottom buttons section -->
    <section>
        <div id="bottom_buttons_section" class="no_print">
            <button onclick="newApt(<?= $invoice_id?>, <?= $car_id ?>)" id="new_apt">New Appointment</button>
        </div>
    </section>
    <?php
}


function invoice_table($conn)
{
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
        <tr onclick="loadInvoice(<?= $invoice_id ?>)">
            <td><?= $invoice_id ?></td>
            <td><?= $cust['f_name'] ?></td>
            <td><?= $cust['l_name'] ?></td>
            <td><?= $apt['start_date'] ?></td>
        </tr>
<?php
    endforeach;
}


function update_apt($conn)
{
    $apt_id = $_POST['apt_id'];

    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $loc = $_POST['loc'];
    $notes = $_POST['notes'];
    $labor_hours = $_POST['labor_hours'];

    $query = "UPDATE `appointments`
            SET `location` = '$loc',
                `notes` = '$notes',
                `labor_hours` = '$labor_hours',
                `start_date` = '$start_date',
                `end_date` = '$end_date'
            WHERE `apt_id` = $apt_id;";

    mysqli_query($conn, $query);
}


function new_apt($conn) {
    $invoice_id = $_POST['invoice_id'];
    $car_id = $_POST['car_id'];

    $query = "INSERT INTO `appointments` (`cust_car_id`, `invoice_id`) VALUES ('$car_id', '$invoice_id');";
    mysqli_query($conn, $query);

    echo mysqli_insert_id($conn);
}


function delete_apt($conn) {
    $apt_id = $_POST['apt_id'];
    $query = "DELETE FROM appointments WHERE apt_id='$apt_id';";
    mysqli_query($conn, $query);
}