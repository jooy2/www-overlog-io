<?php
    header('Content-Type: application/json; charset=utf-8');
    require_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");

    $json = file_get_contents('php://input');
    $dt = json_decode($json, true);
    $result = "";

    if (empty($dt) || empty($dt['id']) || empty($dt['password'])) {
        $result = array("user-exists"=>false, "result"=>"fail: id or password value is broken");
        $result = json_encode($result);
        
        print_r($result);
        return false;
    }

    if (get_method() != "post") {
        $result = array("user-exists"=>false, "result"=>"fail: unexpected request method");
    } else {
        use_database();
        $connection = ConnectDB::getInstance();

        $user_id = $dt['id'];
        $user_pass = $dt['password'];

        if ($connection->auth_check($user_id, $user_pass)) {
            $row = $connection->get_user_data($user_id);
            $result = array("user-exists"=>true, "id"=>$row['u_account'], "name"=>$row['u_name'],
                        "register-date"=>$row['u_reg_date'], "modify-date"=>$row['u_mod_date'],
                        "email"=>$row['u_email'], "result"=>"success");
        } else {
            $result = array("user-exists"=>false, "result"=>"fail: user not exists or wrong password");
        }
    }

    $result = json_encode($result);
    print_r($result);