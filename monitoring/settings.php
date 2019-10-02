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

    if ($row_info['m_is_active'] == "1") {
        $current_check = "checked";
    } else {
        $current_check = "";
    }
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
                    <form class="ui container no-margin pad-2y" id="data-form" method="post" action="action">
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
                <h3>서버 상태<i class="icon icon-pad-left question circle outline popup" data-content="서버의 종료 여부를 선택합니다. 종료 상태에서 로그가 수집되지 않습니다. 자동 수집에 실패하면 자동으로 종료됩니다."></i></h3>
                <div class="inline field">
                    <div id="server-stat" class="ui toggle checkbox <?=$current_check?>">
                        <input type="checkbox" tabindex="0" class="hidden" <?=$current_check?>>
                        <label></label>
                    </div>
                </div>
            </div>
            <div class="ui segment">
                <h3>삭제</h3>
                <p>이 모니터링 장치를 삭제합니다.</p>
                <div id="btn-delete" class="ui red button">삭제하기</div>
            </div>
            <div class="ui tiny modal" id="modal-delete">
                <div class="header">삭제 확인</div>
                <div class="content">
                    <p class="no-select">정말로 이 장치를 삭제하시겠습니까? 아래 입력란에 장치명 <b style="color:blue">'<?=$row_info['m_name']?>'</b>을 그대로 입력한 후, 삭제를 클릭해주세요.</p>
                    <div id="modal-delete-form" class="ui form">
                        <div class="inline field">
                            <label>장치명</label>
                            <input type="text" id="delete-form-dev-name" placeholder="장치 이름">
                        </div>
                    </div>
                    <h3 id="modal-delete-loading" class="hidden">
                        <i class="notched circle loading icon"></i>
                        잠시만 기다려주세요...
                    </h3>
                </div>
                <div id="modal-delete-actions" class="actions">
                    <div id="btn-really-delete" class="ui approve red button">삭제</div>
                    <div class="ui cancel button">취소</div>
                </div>
            </div>
		</section>
	</body>
	<?= load_script_jquery() ?>
    <?= load_script_semantic() ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#btn-delete").click(function() {
                $(".tiny.modal").modal({
                    onApprove: function() {
                        var currentVal = $("#delete-form-dev-name").val();

                        if (currentVal.length == 0 || currentVal != "<?=$row_info['m_name']?>") {
                            $("#delete-form-dev-name").focus();
                            alert("장치 이름을 올바르게 입력해주세요!");
                            return false;
                        }

                        $("#modal-delete-form").addClass("hidden");
                        $("#modal-delete-actions").addClass("hidden");
                        $("#modal-delete-loading").removeClass("hidden");

                        setTimeout(function() {
                            location.href = "action?id=<?=$row_info['m_id']?>&operation=delete";
                        }, 1500);

                        return false;
                    }
                }).modal("show");    
            });

            $(".ui.checkbox").checkbox();

            $("#server-stat").change(function() {
                var checked = 0;
                
                if ($("#server-stat").hasClass("checked") == true)
                    checked = 1;
                else
                    checked = 0;
                
                $.ajax({
                    type: 'GET',
                    url: '../api/conf/server/status',
                    data : {
                        "monitor_id": "<?=$row_info['m_id']?>",
                        "enabled": checked
                    },
                    contentType: 'html',
                    success: function(data) {
                    }
                });
            });
        });
    </script>
</html>
<?php
	get_page('/include/footer');
?>