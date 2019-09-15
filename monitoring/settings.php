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

    $row_info = $connection->get_monitor_info_by_id($monitor_id);
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
            <h2 class="ui header pad-2y">
                <i class="icon"><img src="<?=get_ico($row_info['m_icon'], 48)?>"></i>
                <div class="content">
                    <?=$row_info['m_name']?>
                    <div class="sub header"><?=$row_info['m_desc']?></div>
                </div>
            </h2>
            <div class="ui segment">
                <h3>장치 정보</h3>
                <div class="">
                    <form class="ui container no-margin pad-2y" id="data-form" method="post" action="new">
                        <div class="ui fluid labeled input">
                            <div class="ui label">
                                장치 이름
                            </div>
                            <input id="dev-name" name="dev-name" value="<?=$row_info['m_name']?>" type="text" maxlength="15" placeholder="이름">
                        </div>
                        <div class="ui fluid labeled input">
                            <div class="ui label">
                                장치 설명
                            </div>
                            <input id="dev-desc" name="dev-desc" value="<?=$row_info['m_desc']?>" type="text" maxlength="100" placeholder="설명">
                        </div>
                        <div class="ui disabled fluid labeled input">
                            <div class="ui label">
                                호스트 주소 (존재하는 경우)
                            </div>
                            <input id="dev-hostname" name="dev-hostname" value="<?=$row_info['m_host_domain']?>" maxlength="40" type="text" placeholder="호스트명">
                        </div>
                        <div class="ui disabled fluid labeled input">
                            <div class="ui label">
                                호스트 IP
                            </div>
                            <input id="dev-ip" name="dev-ip" value="<?=$row_info['m_host_ip']?>" maxlength="20" type="text" placeholder="호스트 IP">
                        </div>
                        <input id="step-1-form-value" name="dev-os" type="hidden">
                        <input id="step-2-form-value" name="dev-type" type="hidden">
                        <input id="step-3-form-icon" name="dev-icon" type="hidden">
                        <input id="step-4-form-token" name="dev-token" type="hidden">
                    </form>
                </div>
                <div class="ui button">수정하기</div>
            </div>
            <div class="ui segment">
                <h3>삭제</h3>
                <p>이 모니터링 장치를 삭제합니다.</p>
                <div class="ui red button">삭제하기</div>
            </div>
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