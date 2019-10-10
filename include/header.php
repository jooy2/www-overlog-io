<?php
	require_once("config.php");

	if (is_exist_session()) {
		$auth_menu = "로그아웃";
		$auth_href = "logout";
		$auth_user_info = "<a href='".SITE_HOME."/accounts' class='item'>계정 정보</a>";

        //if (is_admin()) $auth_admin = "<a href='".SITE_HOME."/admin' class='item'>관리자 메뉴</a>";
         $auth_admin = "";
	} else {
		$auth_admin = "";
		$auth_menu = "로그인";
		$auth_href = "login";
		$auth_user_info = "";
	}
?>
<!DOCTYPE html>
<html lang="<?= get_locale() ?>">
	<head>
		<meta charset="<?= get_charset(SET_DEFAULT) ?>">
		<?= load_style_semantic() ?>
		<?= load_style_common() ?>
		<?= load_style_pushbar() ?>
	</head>
	<body>
		<div class="ui top fixed menu">
			<a class="item popup" data-pushbar-target="push-bar" data-content="더보기">
				<i class="th icon no-margin"></i>
			</a>
            <a href="<?=SITE_HOME?>/monitoring" class="item popup" data-content="모니터링">
				<i class="tv icon no-margin" id="menu-icon-monitoring"></i>
			</a>
            <!--
			<a href="<?=SITE_HOME?>/analytics" class="item popup" data-content="로그분석">
				<i class="server icon no-margin" id="menu-icon-analytics"></i>
			</a>-->
			<div class="ui simple dropdown item">
				<i class="user icon" id="menu-icon-user"></i>
				<i class="dropdown icon no-margin"></i>
				<div class="menu">
					<a class="item" href="<?=SITE_HOME?>/auth/<?= $auth_href ?>">
						<?= $auth_menu ?>
					</a>
					<?= $auth_user_info ?>
                    <?= $auth_admin ?>
				</div>
			</div>
		</div>
		<div data-pushbar-id="push-bar" class="pushbar from_left">
            <div data-pushbar-close class="ui icon button big push-bar-button"><i class="close icon"></i></div>
            <div class="aside-title"><h3><i class="tv icon icon-pad"></i>모니터링</h3></div>
            <div id="aside-link-dashboard-monitor" class="aside-menu">대시보드</div>
            <div id="aside-link-monitoring-new" class="aside-menu"><i class="plus square outline icon icon-pad"></i>새 장치 추가</div>
            <!--<div class="aside-title"><h3><i class="server icon icon-pad"></i>로그분석</h3></div>
            <div id="aside-link-dashboard-analytics" class="aside-menu">대시보드</div>
            <div id="aside-link-analytics-new" class="aside-menu"><i class="plus square outline icon icon-pad"></i>새 로그 데이터</div>-->
            <div class="aside-title"><h3><i class="user icon icon-pad"></i>계정</h3></div>
            <div id="aside-link-accounts" class="aside-menu">계정 정보</div>
            <?php
                if (!is_exist_session())
                    echo "<div id='aside-link-log-in' class='aside-menu'><i class='sign-in icon icon-pad'></i>로그인</div>";
                else
                    echo "<div id='aside-link-log-out' class='aside-menu'><i class='sign-out icon icon-pad'></i>로그아웃</div>";
            ?>
            <div class="aside-title"><h3><i class="question circle icon icon-pad"></i>도움말</h3></div>
            <div id="aside-link-download" class="aside-menu">클라이언트 다운로드</div>
            <div id="aside-link-help" class="aside-menu">사용법</div>
		</div>
	</body>
	<?= load_script_jquery() ?>
	<?= load_script_pushbar() ?>
	<?= load_script_semantic() ?>
	<script type="text/javascript">
		$(document).ready(function() {
			var pushbar = new Pushbar({
				blur:true,
				overlay:true
			});

			$(".popup").popup();
			//pushbar.open('push-bar');
			var focusMenuText = $("meta[name='menu-highlight']").attr("content");
			if (focusMenuText != undefined)
                $("#menu-icon-" + focusMenuText).addClass("blue");
                
            $("#aside-link-dashboard-monitor").click(function() {
                location.href = "<?=SITE_HOME?>/monitoring";
            });

            $("#aside-link-monitoring-new").click(function() {
                location.href = "<?=SITE_HOME?>/monitoring/new";
            });

            $("#aside-link-dashboard-analytics").click(function() {
                location.href = "<?=SITE_HOME?>/analytics";
            });

            $("#aside-link-analytics-new").click(function() {
                location.href = "<?=SITE_HOME?>/analytics/new";
            });

            $("#aside-link-accounts").click(function() {
                location.href = "<?=SITE_HOME?>/accounts";
            });

            $("#aside-link-log-in").click(function() {
                location.href = "<?=SITE_HOME?>/auth/login";
            });

            $("#aside-link-log-out").click(function() {
                location.href = "<?=SITE_HOME?>/auth/logout";
            });

            $("#aside-link-help").click(function() {
                location.href = "<?=SITE_HOME?>/help";
            });

            $("#aside-link-download").click(function() {
                location.href = "<?=SITE_HOME?>/downloads";
            });
		});
	</script>
</html>
