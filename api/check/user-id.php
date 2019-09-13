<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
    
	$user_id = $_GET['id'];
	if (empty($user_id)) {
		echo "N";
		return false;
    }

    use_database();
    $connection = ConnectDB::getInstance();
    $test = $connection->user_exist_check($user_id);

    if ($connection->user_exist_check($user_id))
        echo "N";
    else
        echo "Y";