<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/data-type/all.php");
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/upload-helper.php");

    if (get_method() != "post") {
        echo "fail: unexpected request method";
        return false;
    }

    if (!isset($_POST['token'])) {
        echo "fail: empty body data";
        return false;
    }

    $token = $_POST['token'];

    use_database();
    $connection = ConnectDB::getInstance();
    $interpret_data = InterpretData::getInstance();

    $result = array();

    $monitor_info = $connection->get_monitor_info_by_token($token);

    if ($monitor_info == null) {
        echo "fail: token does not exists";
        return false;
    }

    $tempArr = explode("//", $monitor_info);

    $data_id = $tempArr[0];
    $data_type = $tempArr[1];

    if (!empty($_FILES)) {
        $file = new UploadHelper();
        $result = $file->upload();

        // 로그 등록 처리
        $post_data = file_get_contents($result['file_path']);
        error_log(">>>>>>>>>>>>>" . $post_data . "<<<<<<<<<<<<<<<");

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
    }