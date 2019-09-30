<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");

	$auth_success = false;

	if (get_method() == "post"
			&& isset($_POST['current-password']) && isset($_POST['replace-password'])) {
		// Connect database
		use_database();
		
		// Authentication request
		$curr = addslashes($_POST['current-password']);
		$repl = addslashes($_POST['replace-password']);

		$connection = ConnectDB::getInstance();
        $auth_success = $connection->auth_check($user_id, $user_pass);
        $user_data_row = $connection->get_user_data($user_id);
		
		if ($auth_success) {
            $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
            $_SESSION['user-no'] = $user_data_row['u_id'];
			$_SESSION['user-id'] = $user_id;
            $_SESSION['login-date'] = get_datetime();
            $_SESSION['is-admin'] = $user_data_row['u_is_admin'];
            $_SESSION['user-name'] = $user_data_row['u_name'];
            $_SESSION['user-email'] = $user_data_row['u_email'];
            $_SESSION['register-date'] = $user_data_row['u_reg_date'];
            $_SESSION['modify-date'] = $user_data_row['u_mod_date'];
            if ($redirect_href != null)
                echo "<script type='text/javascript'>location.replace('$redirect_href');</script>";
            else
			    echo "<script type='text/javascript'>location.replace('".SITE_HOME."/monitoring');</script>";
		}
	}
?>
<!DOCTYPE html>
<html lang="<?= get_locale() ?>">
	<head>
		<meta charset="<?= get_charset(SET_DEFAULT) ?>">
		<?= load_style_semantic() ?>
		<?= load_style_common() ?>
		<?= get_meta_common() ?>
		<title>암호 변경하기<?= get_site_title() ?></title>
		<meta name="description" content="">
	</head>
	<body>
		<div class="ui middle aligned center aligned grid">
  			<div class="column auth">
				<?php
					if (!$auth_success && get_method() == "post")
						echo message_error_display('올바르지 않은 비밀번호', '현재 비밀번호가 잘못 입력되었습니다.');
				?>
				<form name="form-auth" id="form-auth" action="login?req=<?=$req?>&redirect=<?=$redirect_href?>" method="post" class="ui large form">
                    <div id="form-auth-message" class="ui error message">
                        <i class="close icon"></i>
                        <div class="header">올바르지 않은 비밀번호</div>
                        변경할 비밀번호가 일치하지 않습니다.
                    </div>
					<div class="ui stacked secondary segment">
                        <h3 class="no-select"><i class="lock icon"></i>현재 계정의 비밀번호 변경</h3>
						<div class="field">
							<div class="ui left icon input">
								<i class="lock icon"></i>
								<input class="disable-ime" autofocus autocomplete="off" type="password" name="user-password" id="user-password" placeholder="현재 비밀번호" maxlength="20" pattern="[A-Za-z0-9]*">
							</div>
						</div>
						<div class="field">
							<div class="ui left icon input">
								<i class="lock icon"></i>
								<input type="password" autocomplete="off" name="change-password" id="change-password" placeholder="변경할 비밀번호" maxlength="20">
							</div>
						</div>
                        <div class="field">
							<div class="ui left icon input">
								<i class="lock icon"></i>
								<input type="password" autocomplete="off" name="change-password-repeat" id="change-password-repeat" placeholder="변경할 비밀번호 확인" maxlength="20">
							</div>
						</div>
						<div id="btn-confirm" class="ui fluid large teal submit button">변경하기</div>
                        <div id="btn-cancel" class="ui fluid large red button">취소</div>
					</div>
				</form>
			</div>
		</div>
	</body>
	<?= load_script_common() ?>
	<?= load_script_jquery() ?>
	<?= load_script_semantic() ?>
	<script type="text/javascript">
		$("input[id^='change-password-']").keydown(function (e) {
			if (e.which == 13) {
				$('#btn-confirm').click();
				return false;
			}
		});

		$('#btn-confirm').submit(function() {
			return false;
		});

		$('#btn-confirm').click(function() {
            $("#form-auth-message").removeClass("transition hidden");

			if (formLengthCheck('#change-password', 4) || formLengthCheck('#change-password-repeat', 4))
                return $("#form-auth").addClass("error");
			
			if ($('#change-password').val() != $('#change-password-repeat').val())
                return $("#form-auth").addClass("error");
            
                alert("준비중");
                return false;

			$('#form-auth').submit();
        });
        
        dismiss_message();
        
        $("#btn-cancel").click(function() {
           history.go(-1);
        });
	</script>
</html>