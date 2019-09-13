<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
	get_page('/include/header');
	
    authentication_check(SITE_HOME . "/admin");
    if (!is_admin()) return false;
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
			<div class="b-box-black">
				계정 정보 추가 예정
			</div>
		</section>
	</body>
	<?= load_script_jquery() ?>
	<?= load_script_semantic() ?>
</html>
<?php
	get_page('/include/footer');
?>
