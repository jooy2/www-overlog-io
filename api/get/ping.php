<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
    ini_set('display_errors', '0');

    if (get_method() != "get") {
        echo "fail: unexpected request method";
        return false;
    }

    if (!isset($_GET['host'])) {
        echo "fail: unexpected value";
        return false;
    }

    print(ping_check($_GET['host']));