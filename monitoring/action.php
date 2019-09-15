<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");

    authentication_check(SITE_HOME . "/monitoring");

    if (!isset($_GET['id']) || !isset($_GET['action'])) {
        echo "유효하지 않은 시도입니다!";
        return false;
    }

    if (get_method() == "get") {
        echo "잘못된 요청 방식입니다!";
        return false;
    }
    
    $monitor_id = $_GET['id'];
    $action = $_GET['action'];

    use_database();
    $connection = ConnectDB::getInstance();

    if ($action == "delete") {

    }
?>