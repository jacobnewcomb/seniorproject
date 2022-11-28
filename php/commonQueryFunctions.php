<?php
    $hostname     = "localhost";
    $username     = "senior";
    $password     = "project";
    $database     = "seniorproject";

    #Customer Queries
    function fetchAllCustomers(){
        $conn = mysqli_connect($hostname, $username, $password, $database);
        if (!$conn) {
            die("Unable to Connect database: " . mysqli_connect_error());
        }

        $query = "SELECT * FROM 'customer' WHERE 1";
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
    function fetchCustomerForAppointment($apt_id){
        $conn = mysqli_connect($hostname, $username, $password, $database);
        if (!$conn) {
            die("Unable to Connect database: " . mysqli_connect_error());
        }

        $query = "SELECT A.Cust_id FROM 'appointments' A WHERE A.apt_id = '" . $apt_id . "';";
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
    function fetchAllAppointments(){
        $conn = mysqli_connect($hostname, $username, $password, $database);
        if (!$conn) {
            die("Unable to Connect database: " . mysqli_connect_error());
        }

        $query = "SELECT * FROM 'appointments' WHERE 1";
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
    function fetchAppointmentsForCustomer($cust_id){
        $conn = mysqli_connect($hostname, $username, $password, $database);
        if (!$conn) {
            die("Unable to Connect database: " . mysqli_connect_error());
        }

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
    function fetchAllCars(){
        $conn = mysqli_connect($hostname, $username, $password, $database);
        if (!$conn) {
            die("Unable to Connect database: " . mysqli_connect_error());
        }

        $query = "SELECT * FROM 'cars' WHERE 1";
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
    function fetchCarsForCustomer($cust_id){
        $conn = mysqli_connect($hostname, $username, $password, $database);
        if (!$conn) {
            die("Unable to Connect database: " . mysqli_connect_error());
        }

        $query = "SELECT * FROM 'CustomersCars' WHERE Cust_id = " . $cust_id;
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
    function fetchAllInvoices(){
        $conn = mysqli_connect($hostname, $username, $password, $database);
        if (!$conn) {
            die("Unable to Connect database: " . mysqli_connect_error());
        }

        $query = "SELECT * FROM 'invoices' WHERE 1";
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
    function fetchInvoicesForCustomer($cust_id){
        $conn = mysqli_connect($hostname, $username, $password, $database);
        if (!$conn) {
            die("Unable to Connect database: " . mysqli_connect_error());
        }

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

    #Inventory Queries
    function fetchAllInventory(){
        $conn = mysqli_connect($hostname, $username, $password, $database);
        if (!$conn) {
            die("Unable to Connect database: " . mysqli_connect_error());
        }

        $query = "SELECT * FROM 'inventory' WHERE 1";
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