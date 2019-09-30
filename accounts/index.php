<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
	get_page('/include/header');
	
    authentication_check(SITE_HOME . "/accounts");

    use_database();
    $connection = ConnectDB::getInstance();

    $user_id = get_user_id();
    
    $row = $connection->get_user_data($user_id);
?>
<!DOCTYPE html>
<html lang="<?= get_locale() ?>">
	<head>
		<meta charset="<?= get_charset(SET_DEFAULT) ?>">
		<?= load_style_common() ?>
		<?= get_meta_common() ?>
		<?= highlight_menu('user') ?>
		<title>계정 정보<?= get_site_title() ?></title>
		<meta name="description" content="">
	</head>
	<body class="no-select">
		<section class="ui container">
			<h2 class="ui header pad-2y">
                <i class="user icon"></i>
                <div class="content">
                    내 계정
                    <div class="sub header">계정 정보를 확인하고 수정할 수 있습니다.</div>
                </div>
            </h2>
            <div class="ui items b-box-black">
                <div class="item">
                    <div class="image">
                        <img src="<?=get_profile("default")?>">
                    </div>
                    <div class="content">
                        <h2 id="form-name-value">
                            <i id="form-name-real-value"><?=$row['u_name']?></i>
                            <i id="form-name-edit-btn" class="icon edit outline icon-pad-left clickable"></i>
                        </h2>
                        <div id="form-name-input" class="ui action input hidden">
                            <input type="text" maxlength="20" value="<?=$row['u_name']?>">
                            <button class="ui button"><i class="icon pencil"></i>수정</button>
                        </div>
                        <div class="meta">
                            <span><?=$row['u_account'] . " (" . $row['u_email'] . ")"?></span>
                        </div>
                        <div class="extra">
                            <?=$row['u_reg_date']?> 생성됨
                        </div>
                    </div>
                </div>
            </div>
            <div class="b-box-black">
                <h2>
                    <i class="tags icon"></i>계정 관리
                </h2>
                <button id="btn-change-password" class="ui green button">암호 변경</button>
                <button id="btn-remove-account" class="ui red button">계정 비활성화</button>
            </div>
		</section>
	</body>
	<?= load_script_jquery() ?>
	<?= load_script_semantic() ?>
    <script type="text/javascript">
        $("#form-name-edit-btn").click(function() {
            $("#form-name-value").addClass("hidden");
            $("#form-name-input").removeClass("hidden");
            $("#form-name-input input").focus();
        });

        $("#form-name-input input").keydown(function (e) {
            if (e.which == 13)
                setUserName();
        });

        $("#form-name-input button").click(function() {
            setUserName();
        });

        $("#btn-change-password").click(function() {
            location.href = "change-password";
        });

        function setUserName() {
            var current = "<?=$row['u_name']?>";
            var replace = $("#form-name-input input").val();
            if (current != replace) {
                $.ajax({
                    type: 'GET',
                    url: '../api/conf/user/name',
                    data : {
                        "name_before": current,
                        "name_after": replace
                    },
                    contentType: 'html',
                    success: function(data) {
                        $("#form-name-real-value").text(replace);
                    }
                });
            }
            $("#form-name-value").removeClass("hidden");
            $("#form-name-input").addClass("hidden");
        }
    </script>
</html>
<?php
	get_page('/include/footer');
?>
