<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/upload-helper.php");

$file = $_POST['file'];

if ($file) {
    $path = FILES_PATH.$file;
    if (is_file($path))
        unlink($path);
}