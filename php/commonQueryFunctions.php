<?php
    require_once('databaseConnection.php');

    $action = $_POST["action"];
    if(ISSET($_POST["search"])) $search = $_POST["search"];
    if(ISSET($_POST["cust_id"])) $cust_id = $_POST["cust_id"];
    if(ISSET($_POST["make"])) $make = $_POST["make"];
    if(ISSET($_POST["model"])) $model = $_POST["model"];
    if(ISSET($_POST["year"])) $year = $_POST["year"];
    if(ISSET($_POST["car_id"])) $car_id = $_POST["car_id"];
    $conn = ConnectDb::getInstance();

    switch($action){
        case 'fetchCustomerByParameters': {fetchCustomerByParameters($conn->getConnection(), $search); break;}
        case 'fetchCarByParameters': {if(ISSET($_POST["cust_id"])) fetchCarByParameters($conn->getConnection(), $cust_id, $search); break;}
        case 'fetchExistingMakesTop': {fetchExistingMakesTop($conn->getConnection(), $search); break;}
        case 'fetchExistingModelsTop': {fetchExistingModelsTop($conn->getConnection(), $search); break;}
        case 'searchCars': {if(ISSET($_POST["make"]) && ISSET($_POST["model"]) && ISSET($_POST["year"])) searchCars($conn->getConnection(), $make, $model, $year); break;}
        case 'addCustomersCarRelation': {if(ISSET($_POST["cust_id"]) && ISSET($_POST["car_id"])) addCustomersCarRelation($conn->getConnection(), $cust_id, $car_id); break;}
        default:;
    }
    #Customer Queries
    function fetchAllCustomers($conn){
        $query = "SELECT * FROM 'customer'";
        $exec = mysqli_query($conn, $query);
        $data = array();
        while($row = mysqli_fetch_assoc($exec))
        {
            $data[] = $row;
        }
        $data = array_reverse($data);
        mysqli_close($conn);
        echo json_encode($data);
    }
    function fetchCustomerForAppointment($conn, $apt_id){
        $query = "SELECT C.Cust_id, C.f_name, C.l_name, C.address FROM 'customers' C, 'appointments' A WHERE A.apt_id = '" . $apt_id . "' and A.cust_id = C.cust_id;";
        $exec = mysqli_query($conn, $query);
        $data = array();
        while($row = mysqli_fetch_assoc($exec))
        {
            $data[] = $row;
        }
        $data = array_reverse($data);
        mysqli_close($conn);
        echo json_encode($data);
    }
    function fetchCustomerByParameters($conn, $input){
        $query = "SELECT * FROM `customer` WHERE (f_name LIKE '%" . $input . "%' OR l_name LIKE '%" . $input . "%' OR concat(f_name, ' ', l_name) LIKE '%" . $input . "%' OR cust_id = '" . $input . "' OR phone LIKE '%" . $input . "%') AND ('" . $input . "' != '' AND '" . $input . "' != ' ');";
        $exec = mysqli_query($conn, $query);
        $data = array();
        while($row = mysqli_fetch_assoc($exec))
        {
            $data[] = $row;
        }
        $data = array_reverse($data);
        mysqli_close($conn);
        echo json_encode($data);
    }

    #Appointment Queries
    function fetchAllAppointments($conn){
        $query = "SELECT * FROM 'appointments'";
        $exec = mysqli_query($conn, $query);
        $data = array();
        while($row = mysqli_fetch_assoc($exec))
        {
            $data[] = $row;
        }
        $data = array_reverse($data);
        mysqli_close($conn);
        echo json_encode($data);
    }
    function fetchAppointmentsForCustomer($conn, $cust_id){
        $query = "SELECT * FROM 'appointments' WHERE Cust_id = " . $cust_id;
        $exec = mysqli_query($conn, $query);
        $data = array();
        while($row = mysqli_fetch_assoc($exec))
        {
            $data[] = $row;
        }
        $data = array_reverse($data);
        mysqli_close($conn);
        echo json_encode($data);
    }

    #Car Queries
    function fetchAllCars($conn){
        $query = "SELECT * FROM 'cars'";
        $exec = mysqli_query($conn, $query);
        $data = array();
        while($row = mysqli_fetch_assoc($exec))
        {
            $data[] = $row;
        }
        $data = array_reverse($data);
        mysqli_close($conn);
        echo json_encode($data);
    }
    function fetchCarsForCustomer($conn, $cust_id){
        $query = "SELECT * FROM car natural join customerscar WHERE cust_id = " . $cust_id;
        $exec = mysqli_query($conn, $query);
        $data = array();
        while($row = mysqli_fetch_assoc($exec))
        {
            $data[] = $row;
        }
        $data = array_reverse($data);
        mysqli_close($conn);
        echo json_encode($data);
    }
    function fetchCarByParameters($conn, $cust_id, $input){
        $query = "SELECT Make, Model, Year FROM customerscar cc JOIN car c on cc.car_id = c.id WHERE cust_id = " . $cust_id . " AND (Make LIKE '%" . $input . "%' OR Model LIKE '%" . $input . "%' OR concat(Make, ' ', Model) LIKE '%" . $input . "%' OR concat(Model, ' ', Make) LIKE '%" . $input . "%') AND ('" . $input . "' != '' AND '" . $input . "' != ' ');";
        $exec = mysqli_query($conn, $query);
        $data = array();
        while($row = mysqli_fetch_assoc($exec))
        {
            $data[] = $row;
        }

        $data = array_reverse($data);
        mysqli_close($conn);
        echo json_encode($data);
    }
    function fetchExistingMakesTop($conn, $input){
        $query = "SELECT Make FROM car WHERE Make LIKE '" . $input . "%' AND Make != '" . $input . "'";
        $exec = mysqli_query($conn, $query);
        $data = mysqli_fetch_assoc($exec);
        mysqli_close($conn);
        if($data != NULL) echo $data["Make"];
        else echo "";
    }
    function fetchExistingModelsTop($conn, $input){
        $query = "SELECT Model FROM car WHERE Model LIKE '" . $input . "%' AND Model != '" . $input . "'";
        $exec = mysqli_query($conn, $query);
        $data = mysqli_fetch_assoc($exec);
        mysqli_close($conn);
        if($data != NULL) echo $data["Model"];
        else echo "";
    }
    function searchCars($conn, $make, $model, $year){
        $query = "SELECT id FROM car WHERE Make = '" . $make . "' AND Model = '" . $model . "' AND Year = " . $year;
        $exec = mysqli_query($conn, $query);
        $data = mysqli_fetch_assoc($exec);
        mysqli_close($conn);
        if($data != NULL) echo $data["id"];
        else echo null;
    }
    
    function addCustomersCarRelation($conn, $cust_id, $car_id){
        $query = "INSERT INTO customerscar (cust_id, car_id) VALUES (" . $cust_id . ", " . $car_id . ")";
        $exec = mysqli_query($conn, $query);
        $query = "SELECT id FROM customerscar WHERE cust_id = " . $cust_id . " AND car_id = " . $car_id;
        $exec = mysqli_query($conn, $query);
        $data = mysqli_fetch_assoc($exec);
        mysqli_close($conn);
        if($data != NULL) echo $data["id"];
        else echo null;
    }
    
    #Invoice Queries
    function fetchAllInvoices($conn){
        $query = "SELECT * FROM 'invoices'";
        $exec = mysqli_query($conn, $query);
        $data = array();
        while($row = mysqli_fetch_assoc($exec))
        {
            $data[] = $row;
        }
        $data = array_reverse($data);
        mysqli_close($conn);
        echo json_encode($data);
    }
    function fetchInvoicesForCustomer($conn, $cust_id){
        $query = "SELECT * FROM 'invoices' WHERE Cust_id = " . $cust_id;
        $exec = mysqli_query($conn, $query);
        $data = array();
        while($row = mysqli_fetch_assoc($exec))
        {
            $data[] = $row;
        }
        $data = array_reverse($data);
        mysqli_close($conn);
        echo json_encode($data);
    }
    function fetchInvoicesByParameters($conn, $invoice_date, $start_date, $end_date, $f_name, $l_name){
        $param_number = 0;

        $queryStart = "SELECT * FROM 'customers' C, 'invoices' I WHERE ";
        $query = "";
        if($invoice_date != ""){
            $query = $query . "I.date = '" . $invoice_date . "' ";
            $param_number = $param_number + 1;
        }
        if($start_date != ""){
            if($param_number > 0){
                $query = $query . "AND ";
            }
            $query = $query . "I.start_date = '" . $start_date . "' ";
            $param_number = $param_number + 1;
        }
        if($end_date != ""){
            if($param_number > 0){
                $query = $query . "AND ";
            }
            $query = $query . "I.end_date = '" . $end_date . "' ";
            $param_number = $param_number + 1;
        }
        if($f_name != "" || $l_name != ""){
            if($param_number > 0){
                $query = $query . "AND ";
            }
            $query = $query . "C.cust_id = I.cust.id AND ";
            if($f_name != "" && $l_name != ""){
                $query = $query . "C.f_name = '" . $f_name . "' AND C.l_name = '" . $l_name . "';";
            }
            else if($f_name != ""){
                $query = $query . "C.f_name = '" . $f_name . "';";
            }
            else{
                $query = $query . "C.l_name = '" . $l_name . "';";
            }
        }
        if($param_number == 0){
            $query = "1";
        }
        $query = $queryStart . $query;
        $exec = mysqli_query($conn, $query);
        $data = array();
        while($row = mysqli_fetch_assoc($exec))
        {
            $data[] = $row;
        }
        $data = array_reverse($data);
        mysqli_close($conn);
        echo json_encode($data);
    }
    function fetchPDFByInvoice($conn, $invoice_id){
        $query = "SELECT invoice_path FROM 'invoices' WHERE invoice_id = '" . $invoice_id . "';";
        $exec = mysqli_query($conn, $query);
        $data = array();
        while($row = mysqli_fetch_assoc($exec))
        {
            $data[] = $row;
        }
        $data = array_reverse($data);
        mysqli_close($conn);
        echo json_encode($data);
    }

    #Inventory Queries
    function fetchAllInventory($conn){
        $query = "SELECT * FROM 'inventory'";
        $exec = mysqli_query($conn, $query);
        $data = array();
        while($row = mysqli_fetch_assoc($exec))
        {
            $data[] = $row;
        }
        $data = array_reverse($data);
        mysqli_close($conn);
        echo json_encode($data);
    }

    function fetchItem($conn, $name){
        $query = "SELECT * FROM 'inventory' WHERE name = '" . $name . "';";
        $exec = mysqli_query($conn, $query);
        $data = array();
        while($row = mysqli_fetch_assoc($exec))
        {
            $data[] = $row;
        }
        $data = array_reverse($data);
        mysqli_close($conn);
        echo json_encode($data);
    }

    #Inventory Ledger Queries
    function fetchLedgerInfoByInvoice($conn, $invoice_id){
        $query = "SELECT * FROM 'InventoryLedger' WHERE invoice_id = '" . $invoice_id . "';";
        $exec = mysqli_query($conn, $query);
        $data = array();
        while($row = mysqli_fetch_assoc($exec))
        {
            $data[] = $row;
        }
        $data = array_reverse($data);
        mysqli_close($conn);
        echo json_encode($data);
    }
?>