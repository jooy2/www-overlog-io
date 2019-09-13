<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
	get_page('/include/header');
	authentication_check(SITE_HOME . "/analytics");
?>
<!DOCTYPE html>
<html lang="<?= get_locale() ?>">
	<head>
		<meta charset="<?= get_charset(SET_DEFAULT) ?>">
		<?= load_style_common() ?>
		<?= get_meta_common() ?>
		<?= highlight_menu('analytics') ?>
		<title>로그 분석<?= get_site_title() ?></title>
		<meta name="description" content="">
	</head>
	<body class="no-select">
		<section class="ui container">
			<h2 class="ui header pad-2y">
                <i class="server icon"></i>
                <div class="content">
                    데이터 대시보드
                    <div id="dashboard-counter" class="sub header">0개의 데이터 컬렉션이 저장됨</div>
                </div>
            </h2>
            <a href="new" class="ui primary button">
                <i class="icon plus square outline"></i>
                새로운 로그분석
            </a>
			<div class="b-box-black">
				현재 내 분석 데이터가 없습니다.
			</div>
		</section>
	</body>
	<?= load_script_jquery() ?>
    <?= load_script_semantic() ?>
    <?= load_script_analytics() ?>
</html>
<?php
	get_page('/include/footer');
?>
