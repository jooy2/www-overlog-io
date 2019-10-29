<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
	get_page('/include/header');
	
	authentication_check(SITE_HOME . "/monitoring");

	go_to_page("/monitoring");
?>
<!DOCTYPE html>
<html lang="<?= get_locale() ?>">
	<head>
		<meta charset="<?= get_charset(SET_DEFAULT) ?>">
		<?= load_style_common() ?>
		<?= get_meta_common() ?>
		<?= highlight_menu('main') ?>
		<title>메인<?= get_site_title() ?></title>
		<meta name="description" content="">
	</head>
	<body>
		<section class="ui container">
			<div class="b-box-black">
				인덱스 페이지입니다. 준비중입니다.
			</div>
		</section>
	</body>
	<?= load_script_jquery() ?>
	<?= load_script_semantic() ?>
</html>
<?php
	get_page('/include/footer');
?>
