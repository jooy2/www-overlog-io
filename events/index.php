<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
	get_page('/include/header');
	
    authentication_check(SITE_HOME . "/events");
?>
<!DOCTYPE html>
<html lang="<?= get_locale() ?>">
	<head>
		<meta charset="<?= get_charset(SET_DEFAULT) ?>">
		<?= load_style_common() ?>
		<?= get_meta_common() ?>
		<?= highlight_menu('user') ?>
		<title>이벤트<?= get_site_title() ?></title>
		<meta name="description" content="">
	</head>
	<body class="no-select">
		<section class="ui container">
			<div class="b-box-black">
				추가 예정
			</div>
		</section>
	</body>
	<?= load_script_jquery() ?>
	<?= load_script_semantic() ?>
</html>
<?php
	get_page('/include/footer');
?>
