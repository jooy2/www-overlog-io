<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
	get_page('/include/header');
    
    if (!isset($_GET['id'])) {
        echo "값을 불러오지 못하였습니다!";
        return false;
    }
    
    $monitor_id = $_GET['id'];

    authentication_check(SITE_HOME . "/monitoring/settings?id=" . $monitor_id);
    
    use_database();
    $connection = ConnectDB::getInstance();
?>
<!DOCTYPE html>
<html lang="<?= get_locale() ?>">
	<head>
		<meta charset="<?= get_charset(SET_DEFAULT) ?>">
		<?= load_style_common() ?>
		<?= get_meta_common() ?>
		<?= highlight_menu('monitoring') ?>
		<title>설정<?= get_site_title() ?></title>
		<meta name="description" content="">
	</head>
	<body class="no-select">
		<section class="ui container">
            
		</section>
	</body>
	<?= load_script_jquery() ?>
    <?= load_script_semantic() ?>
    <script type="text/javascript">
        $(document).ready(function () {
            
        });
    </script>
</html>
<?php
	get_page('/include/footer');
?>