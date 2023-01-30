<?php

$hostname = 'localhost';
$username = "senior";
$password = "project";
$database = 'seniorproject';

$conn = mysqli_connect($hostname, $username, $password, $database);

if (!$conn) {
    die('Unable to connect database: ' . mysqli_connect_error());
}

#if (isset($_POST['submit'])) {

# get data
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$phone = $_POST['phone'];

    
# optionals
$addr = $_POST['address'];
$email = $_POST['email'];

# check errors
$errorEmpty = false;
$errorPhone = false;
$errorEmail = false;

if (empty($fname) || empty($lname) || empty($phone)) {

    //if the required fields are empty we must catch the error
    echo "<span style=\"color:red\">Fill All Non-Optional Fields</span>";
    $errorEmpty = true;

} elseif (!preg_match('/^[0-9]{10}+$/', $phone)) {
        
    // phone number is invalid format
    echo "<span style=\"color:red\">Invalid Phone Number Format</span>";
    $errorPhone = true;

} elseif (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {

    // optional email was provided but is invalid format
    echo "<span style=\"color:red\">Invalid Email Format</span>";
    $errorEmail = true;

} else {
        
    // construct tuple 
    $sel = "(`f_name`, `l_name`, `phone`";
    $tup = "('$fname', '$lname', '$phone'";

    if (!empty($addr)) {
        $sel .= ", `address`";
        $tup .= ", '$addr'";
    }

    if (!empty($email)) {
        $sel .= ", `email`";
        $tup .= ", '$email'";
    }

    $sel .= ")";
    $tup .= ")";

    # check if this info already exists
    #$select = "SELECT '$sel' FROM customer WHERE '$sel' == '$tup'";
    #$res = mysqli_query($conn, $select);
        
    #if (empty($res)) {
        // insert the new customer
    $insert = "INSERT INTO `customer` " . $sel . " VALUES " . $tup;
    mysqli_query($conn, $insert);

    echo "<span style=\"color:green\">Customer Added</span>";
    #} else {
    #    echo "<span style=\"color:red\">Customer Already Exists</span>";
    #}
}



?>


<script>
    $("#cust-fname, #cust-lname, #cust-phone, #cust-address, #cust-email").removeClass(); //remove the styles
    
    var errorEmpty = "<?php echo $errorEmpty; ?>";
    var errorEmail = "<?php echo $errorEmail; ?>";
    var errorPhone = "<?php echo $errorPhone; ?>";
    
    if (errorEmpty) {
        $("#cust-fname, #cust-lname, #cust-phone").addClass("input-error");
    }
    if (errorPhone) {
        $("#cust-phone").addClass("input-error");
    }
    if (errorMail) {
        $("#cust-email").addClass("input-error");
    }
    if(!errorEmpty && !errorEmail) {
        // clear inputs
        $("#cust-fname, #cust-lname, #cust-phone, #cust-address, #cust-email").val("");
    }
</script>