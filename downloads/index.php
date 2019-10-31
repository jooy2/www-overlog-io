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
		<?= highlight_menu('help') ?>
		<title>클라이언트 다운로드<?= get_site_title() ?></title>
		<meta name="description" content="">
	</head>
	<body class="no-select">
		<section class="ui container">
            <h2 class="ui header pad-2y">
                <i class="download circle icon"></i>
                <div class="content">
                클라이언트 다운로드
                    <div id="dashboard-counter" class="sub header">로그 수집 클라이언트를 다운로드합니다.</div>
                </div>
            </h2>
			<div class="b-box-black center aligned wide ui cards pad-6y">
				<div class="center aligned pad-6y">
					<h1 class="header-lg">실행만 하면 즉시 수집됩니다.</h1>
					<p class="desc">(* 네트워크 연결이 필요합니다.)</p>
				</div>
				<div class="card center aligned pad-6y pad-6x">
					<div class="center aliged">
                		<img src="<?=get_image("/logo/windows-logo")?>" alt="windows" class="img sm">
					</div>
					<h3>Windows</h3>
					<span id="btn-windows-download" class="ui button blue"><i class="icon download"></i>다운로드 (.exe)</span>
				</div>
				<div class="card center aligned pad-6y pad-6x">
					<div class="center aligned">
	                	<img src="<?=get_image("/logo/linux-logo")?>" alt="linux" class="img sm">
					</div>
					<h3>Linux</h3>
					<span id="btn-linux-download" class="ui button blue"><i class="icon download"></i>다운로드 (.sh)</span>
					<span id="btn-how-to-install-linux" class="ui button"><i class="icon question"></i>설치 방법</span>
				</div>
				<div class="card center aligned pad-6y pad-6x">
					<div class="center aligned">
	                	<img src="<?=get_image("/logo/android-logo")?>" alt="android" class="img sm">
					</div>
					<h3>Android (Viewer)</h3>
					<span id="btn-android-download" class="ui button blue"><i class="icon download"></i>다운로드 (APK)</span>
				</div>
            </div>
		</section>
	</body>
	<script type="text/javascript">
		$("#btn-windows-download").click(function () {
			location.href="<?=SITE_HOME?>/files/client/windows_agent.exe";
		});
		$("#btn-linux-download").click(function () {
			location.href="<?=SITE_HOME?>/files/client/linux_agent.sh";
		});
		$("#btn-how-to-install-linux").click(function () {
			location.href="<?=SITE_HOME?>/help";
		});

		$("#btn-android-download").click(function () {
			location.href="<?=SITE_HOME?>/files/client/android_agent.apk";
		});
	</script>
	<?= load_script_jquery() ?>
	<?= load_script_semantic() ?>
</html>
<?php
	get_page('/include/footer');
?>
