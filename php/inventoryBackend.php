<?php

require_once 'databaseConnection.php';
$instance = ConnectDb::getInstance();
$conn = $instance->getConnection();

switch ($_POST['func']) {
    case "search_inventory":
        search_inventory($conn);
        break;
    case "popup_content":
        popup_content($conn);
        break;
    case "change_quantity":
        change_quantity($conn);
        break;
    case "create_new":
        create_new($conn);
        break;
    case "update_info":
        update_info($conn);
        break;
}

function search_inventory($conn)
{
    $item = $_POST['item'];

    if ($item == "*")
        $query = "SELECT * FROM inventory;";
    else
        $query = "SELECT * FROM inventory WHERE name LIKE '%" . $item . "%';";

    $results = mysqli_query($conn, $query);

    if ($item != "" && mysqli_num_rows($results) > 0) :
        foreach ($results as $items) {
            $low = "";
            if ($items["quantity"] < $items["low_quantity_reminder_level"])
                $low = 'title="Low Quantity!" style="background-color: salmon"';

?>
            <tr <?= $low ?> onclick="popup(<?= $items['item_id'] ?>);">
                <td><?= $items["name"]; ?></td>
                <td>$<?= $items["price_per_unit"]; ?></td>
                <td><?= $items["quantity"]; ?></td>
                <td><?= $items['units']; ?></td>
            </tr>
        <?php }
    else : ?>
        <tr>
            <td colspan="5">No record found...</td>
        </tr>
    <?php endif;
} // end search_inventory


// function to fill the content of the popup
function popup_content($conn)
{
    $id = $_POST['id'];

    $query = "SELECT * FROM inventory WHERE item_id = " . $id . ";";
    $item = mysqli_fetch_assoc(mysqli_query($conn, $query));
    ?>

    <div class="popupBackground" onclick="unpop()"></div>
    <div class="popupMessage">

        <h3>Update Information</h3>
        <form>
            Name: <input name="update_name" type="text" value="<?php echo $item['name'] ?>">
            Price: <input name="update_price" type="number" value="<?php echo $item['price_per_unit'] ?>">
            Units: <input name="update_units" type="text" value="<?php echo $item['units'] ?>">
            Low Quantity Reminder Level: <input name="update_low_level" type="number" value="<?php echo $item['low_quantity_reminder_level'] ?>">
            <button onclick="updateInfo(<?= $item['item_id'] ?>)">Update</button>
        </form>

        <h3>Change Quantity</h3>
        <span>Current Quantity: <?php echo $item['quantity'] ?></span>
        <form>
            Change Amount <input type=number value="0" name="change_input">
            <input name="type" type="radio" value="deposit" id="deposit" checked><label for="deposit">Deposit</label>
            <input name="type" type="radio" value="withdraw" id="withdraw"><label for="withdraw">Withdraw</label>
            <br>
            Expense per Unit <input name="expense_per" type="number">
            <br>
            <input type="text" placeholder="Note (optional)" name="note">
            <button onclick="changeQuantity(<?= $item['item_id'] ?>)">Change Quantity</button>
        </form>
    </div>
<?php
} // end popup_content()

// change quantity function
function change_quantity($conn)
{
    $amount = $_POST['amount'];
    $id = $_POST['id'];
    $type = $_POST['type'];
    $expense_per = $_POST['expense_per'];
    $note = $_POST['note'];

    // enter into ledger
    $t = false;
    if ($type == "withdraw")
        $t = true;

    $query = "INSERT INTO `inventoryledger` (`item_id`, `quantity`, `withdraw`, `expense_per_unit`, `note`) 
        VALUES ('$id', '$amount', '$t', '$expense_per', '$note');";
    
    mysqli_query($conn, $query);

    // change amount
    if ($type == "withdraw")
        $amount *= -1;

    $query = "UPDATE inventory 
        SET quantity = quantity + $amount 
        WHERE item_id = $id;";

    mysqli_query($conn, $query);
    
}

// create new function to create new item
function create_new($conn) {
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $low_level = $_POST['low_level'];
    $units = $_POST['units'];
    $price = $_POST['price'];

    $query = "INSERT INTO `inventory` (`name`, `quantity`, `low_quantity_reminder_level`, `units`, `price_per_unit`) 
        VALUES ('$name', '$quantity', '$low_level', '$units', '$price');";
    
    mysqli_query($conn, $query);
}

// update function
function update_info($conn)
{
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $units = $_POST['units'];
    $low_level = $_POST['low_level'];

    $query = "UPDATE inventory 
        SET `name`='$name', `price_per_unit`='$price', `units`='$units', `low_quantity_reminder_level`='$low_level' 
        WHERE item_id = '$id';";
    
    mysqli_query($conn, $query);
}