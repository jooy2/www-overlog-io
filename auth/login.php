<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");

	$auth_success = false;

	if (is_exist_session()) {
        go_to_page("/");
		return false;
    }
    
    $req = "";
    $redirect_href = null;

    if (isset($_GET['req']))
        $req = $_GET['req'];

    if (isset($_GET['redirect']))
        $redirect_href = $_GET['redirect'];

	if (get_method() == "post"
			&& isset($_POST['user-id']) && isset($_POST['user-password'])) {
		// Connect database
		use_database();
		
		// Authentication request
		$user_id = addslashes($_POST['user-id']);
		$user_pass = addslashes($_POST['user-password']);

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
		<title>로그인<?= get_site_title() ?></title>
		<meta name="description" content="">
	</head>
	<body>
		<div class="ui middle aligned center aligned grid">
  			<div class="column auth">
                <div class="ui row">
                    <a href="<?=get_site_base_url()?>">
                        <img class="ui big image" src="<?=get_logo("horizontal")?>">
                    </a>
                </div>
				<?php
					if (!$auth_success && get_method() == "post")
						echo message_error_display('인증에 문제가 발생함', '입력한 계정이 존재하지 않거나, 비밀번호가 잘못되었습니다.');
				?>
				<form name="form-auth" id="form-auth" action="login?req=<?=$req?>&redirect=<?=$redirect_href?>" method="post" class="ui large form">
                    <div id="form-auth-message" class="ui error message">
                        <i class="close icon"></i>
                        <div class="header">잘못된 형식 또는 비어있음</div>
                        아이디 또는 비밀번호를 올바르게 입력해주세요.
                    </div>
					<div class="ui stacked secondary segment">
                        <p class="no-select"><b>OverLog</b> 계정으로 로그인</p>
						<div class="field">
							<div class="ui left icon input">
								<i class="user icon"></i>
								<input class="disable-ime" autofocus autocomplete="off" type="text" name="user-id" id="user-id" placeholder="계정 아이디" maxlength="20" pattern="[A-Za-z0-9]*">
							</div>
						</div>
						<div class="field">
							<div class="ui left icon input">
								<i class="lock icon"></i>
								<input type="password" autocomplete="off" name="user-password" id="user-password" placeholder="비밀번호" maxlength="20">
							</div>
						</div>
						<div id="btn-auth" class="ui fluid large teal submit button">로그인</div>
					</div>
				</form>
				<div class="ui message">
					테스트 계정은 <b>(id) administrator / (pw) test</b> 입니다.
				</div>
				<div class="ui message">
					새로운 사용자이신가요? <a href="register">계정 만들기</a>
				</div>
			</div>
		</div>
	</body>
	<?= load_script_common() ?>
	<?= load_script_jquery() ?>
	<?= load_script_semantic() ?>
	<script type="text/javascript">
		$('#user-id').on('change', function() {
			$('#user-id').val(filterAlphabetNumber($('#user-id').val()));
		});

		$("input[id^='user-']").keydown(function (e) {
			if (e.which == 13) {
				$('#btn-auth').click();
				return false;
			}
		});

		$('#btn-auth').submit(function() {
			return false;
		});

		$('#btn-auth').click(function() {
            $("#form-auth-message").removeClass("transition hidden");

			if (formLengthCheck('#user-id', 4))
                return $("#form-auth").addClass("error");

			if (formLengthCheck('#user-password', 4))
                return $("#form-auth").addClass("error");

			$('#form-auth').submit();
		});
		dismiss_message();
	</script>
</html>
