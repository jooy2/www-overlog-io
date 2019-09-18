<?php
    header('Content-Type: application/json; charset=utf-8');
    require_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");

	$auth_success = false;

    if (get_method() == "get") {
        $json = file_get_contents('php://input');
        $dt = json_decode($json, true);

        if (isset($dt['id']) && isset($dt['password'])) {
            $result = get_user_by_auth($dt['id'], $dt['password']);
        } else {
            $result = array('user-exists'=>'false', 'result'=>'undefined request value');
        }
    } else if (get_method() == "post"
			&& isset($_POST['id']) && isset($_POST['password'])) {
		$result = get_user_by_auth($_POST['id'], $_POST['password']);
	} else {
        $result = array('user-exists'=>'false', 'result'=>'undefined request value');
    }

    function get_user_by_auth($id, $pass) {
        // Connect database
		use_database();
		
		// Authentication request
		$user_id = addslashes($id);
		$user_pass = addslashes($pass);

		$connection = ConnectDB::getInstance();
        $auth_success = $connection->auth_check($user_id, $user_pass);
        $user_data_row = $connection->get_user_data($user_id);
        
		if ($auth_success) {
            return array('user-exists'=>'true', 'id'=>$user_data_row['u_account'], 'name'=>$user_data_row['u_name'],
                        'register-date'=>$user_data_row['u_reg_date'], 'modify-date'=>$user_data_row['u_mod_date'],
                        'email'=>$user_data_row['u_email'], 'result'=>'success');
		} else {
            return array('user-exists'=>'false', 'result'=>'user not exist or uncorrect password');
        }
    }

    $result = json_encode($result);
    print_r($result);