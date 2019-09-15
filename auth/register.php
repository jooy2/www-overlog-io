<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
    
    function post_validation_check() {
        if (isset($_POST['user-id'])
                && isset($_POST['user-password'])
                && isset($_POST['user-name'])
                && isset($_POST['user-email']))
                return true;
        else
            return false;
    }

    $current_method = get_method();
    $register_success = false;

    if ($current_method == "post" && post_validation_check()) {
        // Connect database
		use_database();
		
		// Authentication request
		$user_id = addslashes($_POST['user-id']);
        $user_pass = addslashes($_POST['user-password']);
        $user_name = addslashes($_POST['user-name']);
        $user_email = addslashes($_POST['user-email']);

		$connection = ConnectDB::getInstance();
		$register_success = $connection->register_user($user_id, $user_pass, $user_name, $user_email);
		
		if ($register_success)
            echo "<script type='text/javascript'>location.replace('".SITE_HOME."/auth/welcome');</script>";
    }
?>
<!DOCTYPE html>
<html lang="<?= get_locale() ?>">
	<head>
		<meta charset="<?= get_charset(SET_DEFAULT) ?>">
		<?= load_style_semantic() ?>
		<?= load_style_common() ?>
		<?= get_meta_common() ?>
		<title>계정 생성<?= get_site_title() ?></title>
		<meta name="description" content="">
	</head>
	<body>
		<div class="ui middle aligned center aligned grid">
                <div class="column auth">
                <div class="ui row">
                    <a href="<?=get_site_base_url()?>">
                        <img class="ui big image" src="<?=get_logo("overlog-logo-horizontal")?>">
                    </a>
                </div>
                <?php
					if (!$register_success && get_method() == "post")
						echo message_error_display('계정 생성에 문제가 발생함', '올바르지 않은 요청입니다.');
				?>
                <form id="form-auth" name="form-auth" action="register" method="post" class="ui large form">
                    <div class="ui stacked secondary segment">
                        <p class="no-select">새로운 <b>OverLog</b> 계정 생성하기</p>
                        <div class="field">
                            <div class="ui left icon input">
                                <i class="user icon"></i>
                                <input class="disable-ime" type="text" id="user-id" name="user-id" placeholder="계정 아이디" maxlength="20">
                            </div>
                            <p id="user-exists" class="ui red" style="display:none">이미 존재하는 계정입니다. 다른 아이디를 고려해보세요.</p>
                        </div>
                        <div class="field">
                            <div class="ui left icon input">
                                <i class="lock icon"></i>
                                <input type="password" id="user-password" name="user-password" placeholder="비밀번호" maxlength="20">
                            </div>
                        </div>
                        <div class="field">
                            <div class="ui left icon input">
                                <input type="password" id="user-password-repeat" name="user-password-repeat" placeholder="비밀번호 확인" maxlength="20">
                            </div>
                        </div>
                        <div class="pad-3y">
                        </div>
                        <div class="field">
                            <div class="ui left icon input">
                                <i class="mail icon"></i>
                                <input type="email" id="user-email" name="user-email" placeholder="이메일" maxlength="50">
                            </div>
                        </div>
                        <div class="field">
                            <div class="ui left icon input">
                                <i class="user icon"></i>
                                <input type="text" id="user-name" name="user-name" placeholder="이름" maxlength="20">
                            </div>
                        </div>
                        <div id="btn-auth" class="ui fluid large teal submit button disabled">계정 만들기</div>
                    </div>
                    <div class="ui error message"></div>
                </form>
                <div class="ui message">
                    이미 계정을 가지고 계신가요? <a href="login">로그인</a>
                </div>
            </div>
        </div>
	</body>
    <?= load_script_common() ?>
	<?= load_script_jquery() ?>
	<?= load_script_semantic() ?>
	<script type="text/javascript">
        var disabledSubmit = false;
        $('#user-id').change(function () {
            $('#user-id').val(filterAlphabetNumber($('#user-id').val()));
			$.ajax({
				url:'/api/check/user-id',
				type:'get',
				data:{'id':$('#user-id').val()},
				success: function(data) {
					if (data == "N") {
						$('#user-exists').css('display', 'block');
						$('#btn-auth').addClass('disabled');
						disabledSubmit = true;
					}
					else {
						$('#user-exists').css('display', 'none');
						$('#btn-auth').removeClass('disabled');
						disabledSubmit = false;
					}
				}
			});
        });
        
        $('#user-name').on('change', function() {
			$('#user-name').val(filterSpecialChar($('#user-name').val()));
        });
        
        $('#btn-auth').submit(function () {
            return false;
        });

		$('#btn-auth').click(function() {
            if (disabledSubmit)
                return;

			if (formLengthCheck('#user-id', 4))
                return alertEmptyFormValue('#user-id', '아이디를 입력하지 않았거나 너무 짧습니다! (4-20자)');
                
            if (formLengthCheck('#user-password', 4))
                return alertEmptyFormValue('#user-password', '비밀번호를 입력하지 않았거나 너무 짧습니다! (6-20자)');

            if ($('#user-password').val() != $('#user-password-repeat').val())
                return alertEmptyFormValue('#user-password-repeat', '비밀번호 확인란에 입력한 값이 다릅니다!');
                
            if (formLengthCheck('#user-email', 8))
                return alertEmptyFormValue('#user-email', '올바른 이메일 주소를 입력해주세요!');

            if (formLengthCheck('#user-name', 2))
				return alertEmptyFormValue('#user-name', '올바른 이름을 입력해주세요!');

            if (!isEmailAddress($('#user-email').val()))
                return alertEmptyFormValue('#user-email', '유효한 이메일이 아닙니다!');

            $('#form-auth').submit();
		});
	</script>
</html>