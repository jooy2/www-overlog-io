<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");

    authentication_check(SITE_HOME . "/monitoring");

    if (!isset($_GET['id']) && !isset($_GET['action'])) {
        echo "유효하지 않은 시도입니다!";
        return false;
    }

    if (get_method() != "post" && get_method() != "get") {
        echo "잘못된 요청 방식입니다!";
        return false;
    }
    
    $monitor_id = $_GET['id'];
    $operation = $_GET['operation'];

    use_database();
    $connection = ConnectDB::getInstance();

    if ($operation == "modify") {
        // Modify
        
    } else if ($operation == "delete") {
        // Delete
        if (!$connection->del_monitor_by_id($monitor_id)) {
            echo "알 수 없는 에러 발생!";
            return false;
        }
    }

    echo "<script type='text/javascript'>location.href='details?id=$monitor_id'</script>";
?>