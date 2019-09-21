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
            <div class="ui items">
                <div class="item">
                    <div class="image">
                        <img src="<?=get_profile("default")?>">
                    </div>
                    <div class="content">
                        <h2 class="header"><?=$row['u_account']?></h2>
                        <div class="meta">
                            <span><?=$row['u_name']?></span>
                        </div>
                        <div class="description">
                            <p><?=$row['u_email']?></p>
                        </div>
                        <div class="extra">
                            <?=$row['u_reg_date']?> 생성됨
                        </div>
                    </div>
                </div>
            </div>
		</section>
	</body>
	<?= load_script_jquery() ?>
	<?= load_script_semantic() ?>
</html>
<?php
	get_page('/include/footer');
?>
