<?php
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
        $query = "SELECT C.Cust_id, C.f_name, C.l_name, C.address FROM 'customers' C, 'appointments' A WHERE A.Apt_id = '" . $apt_id . "' and A.Cust_id = C.Cust_id;";
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
        $query = "SELECT * FROM 'customer' WHERE f_name = '" . $input . "' OR l_name = '" . $input . "' OR cust_id = '" . $input . "' OR phone_number = '" . $input . "';";
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
    function fetchCarByParameters($conn, $make, $model, $year){
        if($make != "" && $model != "" && $year != ""){
            $query = "SELECT * FROM 'cars' WHERE (CHARINDEX('" . $make . "', MakeModel) = 1 AND CHARINDEX('" . $model . "', MakeModel) > 1 AND year = '" . $year . "');";
        }
        else if($make != "" && $model != ""){
            $query = "SELECT * FROM 'cars' WHERE (CHARINDEX('" . $make . "', MakeModel) = 1 AND CHARINDEX('" . $model . "', MakeModel) > 1);";
        }
        else if($make != "" && $year != ""){
            $query = "SELECT * FROM 'cars' WHERE (CHARINDEX('" . $make . "', MakeModel) = 1 AND year = '" . $year . "');";
        }
        else if($model != "" && $year != ""){
            $query = "SELECT * FROM 'cars' WHERE (CHARINDEX('" . $model . "', MakeModel) > 1 AND year = '" . $year . "');";
        }
        else if($make != ""){
            $query = "SELECT * FROM 'cars' WHERE (CHARINDEX('" . $make . "', MakeModel) = 1);";
        }
        else if($model != ""){
            $query = "SELECT * FROM 'cars' WHERE (CHARINDEX('" . $model . "', MakeModel) > 1);";
        }
        else if($year != ""){
            $query = "SELECT * FROM 'cars' WHERE (year = '" . $year . "');";
        }
        else{
            $query = "SELECT * FROM 'cars'";
        }
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