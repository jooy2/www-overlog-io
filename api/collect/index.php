<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/data-type/all.php");

    use_database();
    $connection = ConnectDB::getInstance();
    $interpret_data = InterpretData::getInstance();

    $post_data = file_get_contents('php://input');
    $type = $_GET['type'];
    $token = $_GET['token'];

    if (strlen($type) == 0 || strlen($token) == 0) {
        echo "수집 형식 또는 토큰 값이 일치하지 않습니다!";
        return false;
    }
    
    if (strlen($post_data) == 0 || get_method() != "post") {
        echo "POST 요청으로 보내주세요. 또한 BODY 값이 비어있으면 안됩니다.";
        return false;
    }

    $monitor_info = $connection->get_monitor_info_by_token($token);
    $tempArr = explode("//", $monitor_info);
    $data_id = $tempArr[0];
    $data_type = $tempArr[1];

    if ($data_type == null) {
        echo "수집 형식 또는 토큰 값이 일치하지 않습니다!";
        return false;
    }

    switch ($data_type) {
        case 0:
            echo "데이터 타입이 지정되지 않음!";
            break;
        case 1:
            $array_data = $interpret_data->kky_to_array($post_data);
            break;
        default:
            echo "잘못된 데이터 타입입니다!";
            return false;
    }

    // TODO 데이터 보내기
    if ($connection->send_log_monitor($data_id, $array_data)) {
        echo "수집 성공";
    } else {
        echo "수집 실패. 데이터에 문제가 있습니다.";
    }
?>