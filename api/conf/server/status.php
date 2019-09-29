<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");

    use_database();
    $connection = ConnectDB::getInstance();

    if (get_method() != "get") {
        echo "fail: unexpected request method";
        return false;
    }

    if (!isset($_GET['monitor_id'])) {
        echo "fail: empty body data";
        return false;
    }

    if (!isset($_GET['enabled'])) {
        echo "fail: empty body data";
        return false;
    }

    $monitor_id = $_GET['monitor_id'];
    $enabled = $_GET['enabled'];

    if ($enabled != 0 && $enabled != 1) {
        echo "fail: empty enabled data";
        return false;
    }

    if ($connection->set_server_status($monitor_id, $enabled)) {
        echo "success";
    } else {
        echo "fail: unknown error";
    }