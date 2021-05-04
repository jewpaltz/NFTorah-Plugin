<?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

    $controller = $_REQUEST['controller'];
    $action = $_REQUEST['action'];

    $json = file_get_contents('php://input');

    $data = json_decode($json);

    $conn = require('./connection.php');


    $rs = $conn->query('SHOW TABLES');
    while ($table = $rs->fetch_assoc()) {
        print_r($table);
    }
    switch ($controller) {
        case 'purchase':
            NFTorah::PurchaseFormSave($data->purchase, $data->letters);
            echo 'Success';
            break;
        
        default:
            # code...
            break;
    }