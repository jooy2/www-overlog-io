<?php
	include_once('config.php');

	$current_menu = "";

	function use_database() {
		require_once($_SERVER['DOCUMENT_ROOT'] . "/include/connect-db.php");
	}
	
	function get_site_name() {
		return "오버로그";
	}

	function get_site_title() {
		return " - " . get_site_name();
	}
		
	function get_site_base_url() {
		return SITE_HOME;
	}

	function get_api_base_url() {
		return SITE_API;
	}
	
	function load_style_common() {
		return "<link rel='stylesheet' type='text/css' href='".get_site_base_url()."/include/css/common.css'>";
	}

	function load_style_semantic() {
		return "<link rel='stylesheet' type='text/css' href='".get_site_base_url()."/include/semantic/semantic.min.css'>";
	}
	
	function load_style_pushbar() {
		return "<link rel='stylesheet' type='text/css' href='".get_site_base_url()."/include/css/pushbar.css'>";
	}

	function load_style_tui_chart() {
		return "<link rel='stylesheet' type='text/css' href='".get_site_base_url()."/include/css/tui-chart.min.css'>";
	}

	function load_style_datatables() {
		return "<link rel='stylesheet' type='text/css' href='".get_site_base_url()."/include/datatables/datatables.min.css'>";
	}

	function load_style_dropzone() {
		return "<link rel='stylesheet' type='text/css' href='".get_site_base_url()."/include/css/dropzone.css'>";
	}

	function load_script_semantic() {
		return "<script src='".get_site_base_url()."/include/semantic/semantic.min.js'></script>";
	}

	function load_script_common() {
		return "<script src='".get_site_base_url()."/include/js/app-common.js'></script>";
	}

	function load_script_jquery() {
		return "<script src='".get_site_base_url()."/include/js/jquery-3.4.1.min.js'></script>";
    }
    
    function load_script_analytics() {
		return "<script src='".get_site_base_url()."/include/js/app-analytics.js'></script>";
	}

	function load_script_pushbar() {
		return "<script src='".get_site_base_url()."/include/js/pushbar.js'></script>";
	}

	function load_script_app_chart() {
		return "<script src='".get_site_base_url()."/include/js/app-chart.js'></script>";
	}

	function load_script_tui_chart() {
		return "<script src='".get_site_base_url()."/include/js/tui-chart-all.min.js'></script>";
	}

	function load_script_sortable() {
		return "<script src='".get_site_base_url()."/include/js/Sortable.min.js'></script>";
	}

    function load_script_dropzone() {
		return "<script src='".get_site_base_url()."/include/js/dropzone.js'></script>";
    }
    
	function load_script_datatables() {
		return "<script src='".get_site_base_url()."/include/datatables/datatables.min.js'></script>";
	}

	function get_meta_common() {
		echo get_site_keywords();
		echo get_site_viewport();
		echo get_site_canonical();
		echo get_site_favicon();
        echo disable_site_robots();
        echo get_site_compatible_edge();
	}

	function disable_site_robots() {
		return "<meta name='robots' content='noindex, nofollow'>";
	}

	function highlight_menu($str) {
		return "<meta name='menu-highlight' content='$str'>";
	}
	
	function get_site_keywords() {
		return "<meta name='keyword' content=''>";
	}
	
	function get_site_viewport() {
		return "<meta name='viewport' content='width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no'>";
	}
	
	function get_site_canonical() {
		return "<meta name='canonical' content='".get_site_base_url()."'>";
	}
	
	function get_site_favicon() {
		return "<link rel='shortcut icon' href='".get_site_base_url()."/favicon.ico' type='image/x-icon'>";
    }
    
    function get_site_compatible_edge() {
        return "<meta http-equiv='X-UA-Compatible' content='IE=edge'>";
    }

	function get_logo($src) {
		return SITE_IMG . "/logo/overlog-logo-$src.png";
	}

	function get_image($src) {
		return SITE_IMG . $src . ".png";
	}

	function get_ico($src, $size) {
		return SITE_IMG . "/icons/$size/$src.png";
	}

	function get_profile($src) {
		return SITE_IMG . "/users/$src.png";
	}

	function get_process($name) {
		$name = strtolower($name);
		$url = SITE_IMG . "/process/$name.png";

		if (url_exists($url))
			return $url;
		else
			return SITE_IMG . "/process/default.png";
	}

	function url_exists($url) {

        $handle = curl_init($url);
        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

        $response = curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if ($httpCode >= 200 && $httpCode < 400) {
            return true;
        } else {
            return false;
        }

        curl_close($handle);
	}
	
	function ping_check($domain) {
	   $starttime = microtime(true);
	   $file = fsockopen($domain, 80, $errno, $errstr, 2);
	   $stoptime = microtime(true);
	   $status = 0;

	   if (!$file) {
		   $status = -1;
	   } else {
		   fclose($file);
		   $status = ($stoptime - $starttime) * 1000;
		   $status = floor($status);
	   }
	   return $status;
	}   

	function get_page($url) {
		try {
			require_once($_SERVER['DOCUMENT_ROOT'] . $url . '.php');
			return true;
		} catch (Exception $e) {
			$e->getMessage();
			return false;
		}
	}

	function get_file($url) {
		$c = file_get_contents($_SERVER['DOCUMENT_ROOT'] . $url);
		if ($c !== false) {
			echo $c;
			return true;
		}
		else {
			return "Get file error";
		}
	}
	
	function get_locale() {
		return "ko";
	}

	function get_charset($type) {
		if ($type == 0)
			return "UTF-8";
		else
			return "UTF-8";
    }
    
    function get_user_no() {
        if (isset($_SESSION['user-no']))
            return $_SESSION['user-no'];
        else
            return 0;
    }

    function get_user_id() {
        if (isset($_SESSION['user-id']))
            return $_SESSION['user-id'];
        else
            return null;
    }

	function get_method() {
		return strtolower($_SERVER['REQUEST_METHOD']);
	}

	function get_datetime() {
		return date("Y-m-d H:i:s");
	}

	function get_date() {
		return date("Y-m-d");
    }
    
    function is_admin() {
        if (isset($_SESSION['is-admin']) && $_SESSION['is-admin'] == 1)
            return true;
        else
            return false;
    }

	function message_error_display($title, $message) {
		return "<div class='ui error message'>
					<i class='close icon'></i>
					<div class='header'>$title</div>
					$message
				</div>";
	}

	function authentication_check($redirectLink) {
		if (!isset($_SESSION['token'])) {
			echo "<script>location.href='".get_site_base_url()."/auth/login?req=true&redirect=".$redirectLink."';</script>";
			return false;
		}
		return true;
	}

	function is_exist_session() {
		return isset($_SESSION['token']);
	}

	function go_to_page($url) {
		if ($url == "/") {
			echo "<script type='text/javascript'>location.replace('/');</script>";
		} else {
			echo "<script type='text/javascript'>location.replace('".get_site_base_url() . $url."');</script>";
		}
	}
?>
