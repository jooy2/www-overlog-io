<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
    get_page('/include/header');
    
	if (!isset($_GET['id'])) {
        echo "값을 불러오지 못하였습니다!";
        return false;
    }
    
    $monitor_id = $_GET['id'];

    authentication_check(SITE_HOME . "/monitoring/detail?id=" . $monitor_id);
    
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
		<title>정보<?= get_site_title() ?></title>
		<meta name="description" content="">
	</head>
	<body class="no-select">
		<section class="ui container">
            
		</section>
	</body>
	<?= load_script_jquery() ?>
    <?= load_script_semantic() ?>
    <?= load_script_tui_chart() ?>
    <?= load_script_app_chart() ?>
    <script type="text/javascript">
        $(document).ready(function () {
            
        });
    </script>
</html>
<?php
	get_page('/include/footer');
?>