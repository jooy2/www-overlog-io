<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");

	if (!is_exist_session()) {
		echo "<script type='text/javascript'>location.replace('".SITE_HOME."');</script>";
		return false;
	}

    // User data
    unset($_SESSION['token']);
    unset($_SESSION['user-no']);
	unset($_SESSION['user-id']);
    unset($_SESSION['login-date']);
    unset($_SESSION['is-admin']);
    unset($_SESSION['user-name']);
    unset($_SESSION['user-email']);
    unset($_SESSION['register-date']);
    unset($_SESSION['modify-date']);
    session_destroy();
    
    echo "<script type='text/javascript'>location.replace('".SITE_HOME."');</script>";
?>