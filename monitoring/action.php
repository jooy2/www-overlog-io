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
    $operation = $_GET['action'];

    use_database();
    $connection = ConnectDB::getInstance();

    if ($operation == "modify") {
        // Modify
		$dev_name = $_POST['dev-name'];
		$dev_desc = $_POST['dev-desc'];
		$dev_hostname = $_POST['dev-hostname'];
		$dev_ip = $_POST['dev-ip'];
		$connection->modify_device($monitor_id, $dev_name, $dev_desc, $dev_ip, $dev_hostname);	
    	echo "<script type='text/javascript'>location.href='settings?id=$monitor_id'</script>";
    } else if ($operation == "delete") {
        // Delete
        if (!$connection->del_monitor_by_id($monitor_id)) {
            echo "알 수 없는 에러 발생!";
            return false;
        }
    	echo "<script type='text/javascript'>location.href='details?id=$monitor_id'</script>";
    }

    echo "<script type='text/javascript'>location.href='details?id=$monitor_id'</script>";
?>
