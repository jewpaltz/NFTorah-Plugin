<?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

    $controller = $_REQUEST['controller'];
    $action = $_REQUEST['action'];

    $conn = require('./connection.php');


    $rs = $conn->query('SHOW TABLES');
    while ($table = $rs->fetch_assoc()) {
        print_r($table);
    }
    switch ($controller) {
        case 'purchase':
            
            break;
        
        default:
            # code...
            break;
    }