<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/data-type/all.php");

    use_database();
    $connection = ConnectDB::getInstance();
    $interpret_data = InterpretData::getInstance();

    $post_data = file_get_contents('php://input');
    $type = $_GET['type'];
    $token = $_GET['token'];

    if (strlen($type) == 0 || strlen($token) == 0) {
        echo "fail: unexpected parameter";
        return false;
    }
    
    if (get_method() != "post") {
        echo "fail: unexpected request method";
        return false;
    }

    if (strlen($post_data) == 0) {
        echo "fail: empty body data";
        return false;
    }

    $monitor_info = $connection->get_monitor_info_by_token($token);

    if ($monitor_info == "denied") {
        echo "fail: server is closed";
        return false;
    }

    if ($monitor_info == null) {
        echo "fail: token does not exists";
        return false;
    }

    $tempArr = explode("//", $monitor_info);

    $data_id = $tempArr[0];
    $data_type = $tempArr[1];

    if ($data_type == null) {
        echo "fail: token does not exists";
        return false;
    }

    switch ($data_type) {
        case 0:
            echo "fail: wrong data type";
            break;
        case 1:
            $array_data = $interpret_data->kky_to_array($post_data);
            break;
        default:
            echo "fail: wrong data type";
            return false;
    }
    
    if ($connection->send_log_monitor($data_id, $array_data)) {
        echo "success";
    } else {
        echo "fail: unrecognized data";
    }
?>