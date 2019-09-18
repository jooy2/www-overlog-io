<?php
    header('Content-Type: application/json; charset=utf-8');
    require_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");

    // Connect database
	use_database();

	$connection = ConnectDB::getInstance();
    $result = $connection->get_log_monitor_by_id_json(1);

    $result = json_encode($result);
    print_r($result);