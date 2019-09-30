<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");

    use_database();
    $connection = ConnectDB::getInstance();

    if (get_method() != "get") {
        echo "fail: unexpected request method";
        return false;
    }

    if (!isset($_GET['name_before'])) {
        echo "fail: empty body data";
        return false;
    }

    if (!isset($_GET['name_after'])) {
        echo "fail: empty body data";
        return false;
    }

    $name_b = $_GET['name_before'];
    $name_a = $_GET['name_after'];

    if ($connection->set_user_name($name_b, $name_a)) {
        echo "success";
    } else {
        echo "fail: unknown error";
    }