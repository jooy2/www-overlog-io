<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
	get_page('/include/header');
	
    authentication_check(SITE_HOME . "/monitoring");
    
    use_database();
    $connection = ConnectDB::getInstance();
?>
<!DOCTYPE html>
<html lang="<?= get_locale() ?>">
	<head>
		<meta charset="<?= get_charset(SET_DEFAULT) ?>">
		<?= load_style_common() ?>
        <?= load_style_tui_chart() ?>
		<?= get_meta_common() ?>
		<?= highlight_menu('monitoring') ?>
		<title>모니터링 대시보드<?= get_site_title() ?></title>
		<meta name="description" content="">
	</head>
	<body class="no-select">
		<section class="ui container">
            <h2 class="ui header pad-2y">
                <i class="tv icon"></i>
                <div class="content">
                    모니터링 대시보드
                    <div id="dashboard-counter" class="sub header">0개의 모니터링 장치 생성됨</div>
                </div>
            </h2>
            <a href="new" class="ui primary button">
                <i class="icon plus square outline"></i>
                새로운 모니터링
            </a>
            <div class="ui three stackable doubling cards mar-1y" id="sort-grid">
                <?php
                    if (get_user_id() != null)
                        print($connection->get_dashboard_data());
                ?>
            </div>
		</section>
	</body>
	<?= load_script_jquery() ?>
    <?= load_script_semantic() ?>    
    <?= load_script_tui_chart() ?>
    <?= load_script_app_chart() ?>
    <?= load_script_sortable() ?>
    <script type="text/javascript">
        $(document).ready(function() {
            var el = document.getElementById('sort-grid');

            var sortable = Sortable.create(el, {
                animation: 250,
                ghostClass: 'opacity-3'
            });

            $(".btn-monitoring-details").click(function() {
                location.href = "<?=SITE_HOME?>/monitoring/details?id=" + $(this).data("monitoring-id");
            });

            $(".btn-monitoring-settings").click(function() {
                location.href = "<?=SITE_HOME?>/monitoring/settings?id=" + $(this).data("monitoring-id");
            });

            $("#dashboard-counter").html("<strong>" + $("#list-count").text() + "</strong> 개의 모니터링 장치 생성됨");
        })
    </script>
</html>
<?php
	get_page('/include/footer');
?>