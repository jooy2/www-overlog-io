<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
	get_page('/include/header');
?>
<!DOCTYPE html>
<html lang="<?= get_locale() ?>">
	<head>
		<meta charset="<?= get_charset(SET_DEFAULT) ?>">
		<?= load_style_common() ?>
		<?= get_meta_common() ?>
		<title>알 수 없는 페이지<?= get_site_title() ?></title>
		<meta name="description" content="">
	</head>
	<body>
		<section class="ui container">
			<div class="b-box-black">
                <h1>페이지를 찾을 수 없습니다.</h1>
                <p>페이지가 존재하지 않거나, 접근할 수 없습니다.</p>
                <p>올바른 URL로 접근을 시도했는지 확인해주세요.</p>
			</div>
		</section>
	</body>
	<?= load_script_jquery() ?>
	<?= load_script_semantic() ?>
</html>
<?php
	get_page('/include/footer');
?>
