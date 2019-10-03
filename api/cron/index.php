<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/data-type/all.php");

	if ($_SERVER['REMOTE_ADDR'] != "52.78.192.102") {
		echo "fail: access denied";
		return false;
	}

    if (get_method() != "get") {
        echo "fail: unexpected request method";
        return false;
    }

	// File processing
	$dir = "/home/nginx/www/uploads/logs";
	$url_dir = "../../../uploads/logs/";
	$handle = opendir($dir);
	$files = array();

	// Saving file where directory included
	while (false !== ($filename = readdir($handle))) {
		if ($filename == "." || $filename == "..") {
			continue;
		}
	 
	// Adding file from list if is file
		if (is_file($dir . "/" . $filename)) {
			$files[] = $filename;
		}
	}

	closedir($handle);
	sort($files);

	use_database();
	$connection = ConnectDB::getInstance();
	$interpret_data = InterpretData::getInstance();

	foreach ($files as $f) {
		$data = file_get_contents($url_dir . $f);

		$array_data = $interpret_data->kky_to_array($data);
	
		if (strlen($array_data[0]) > 7) {
			$host_ip = $array_data[0];
			$data_id = $connection->get_monitor_info_by_ip($host_ip);

			if ($data_id == -1) {
				echo "fail: ". $url_dir . $f ." (server is dead)\r\n";
				unlink($url_dir . $f);
				continue;
			}

			if ($data_id == null) {
				$os_id = $connection->get_operation_type_id(strtolower($array_data[1]));

				if ($os_id == null) {
					echo "fail: ". $url_dir . $f ." (unknown operation name)\r\n";
					unlink($url_dir . $f);
					continue;
				}

				$token = $connection->create_unknown_device($array_data[2], $array_data[0], $os_id);
				
				if ($token != null) {
					echo "added: ". $url_dir . $f ." (new device: '".$array_data[0]."')\r\n";

					$monitor_info = $connection->get_monitor_info_by_token($token);
					$tempArr = explode("//", $monitor_info);
					$data_id = $tempArr[0];

					if ($data_id == -1) {
						echo "fail: ". $url_dir . $f ." (unknown error)\r\n";
						unlink($url_dir . $f);
						continue;
					}
	
				} else {
					echo "fail: ". $url_dir . $f ." (cannot create device)\r\n";
					unlink($url_dir . $f);
					continue;	
				}
			}
		
			if ($connection->send_log_monitor($data_id, $array_data))
        		echo "success: " . $url_dir . $f . "\r\n";
			else
		        echo "fail: unrecognized data";

			unlink($url_dir . $f);
		} else {
			echo "fail: ". $url_dir . $f ." (host ip is unknown)\r\n";
			unlink($url_dir . $f);
			continue;
		}
	}

	echo "all progress succeed\r\n";
