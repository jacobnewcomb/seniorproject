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
                    <button onclick="popup(<?= $apt['apt_id'] ?>)">Edit</button>
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
            <span>Total Labor Hours: <?= $total ?></span>
        </div>
    </section>

    <!-- bottom buttons section -->
    <section>
        <div id="bottom_buttons_section">
            <button id="finalButton">Download</button>
            <button id="omitButton">Delete</button>
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
    echo "fff";
}
