<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
    get_page('/include/header');
    
	if (!isset($_GET['id'])) {
        echo "값을 불러오지 못하였습니다!";
        return false;
    }
    
    $monitor_id = $_GET['id'];

    authentication_check(SITE_HOME . "/monitoring/details?id=" . $monitor_id);
    
    use_database();
    $connection = ConnectDB::getInstance();

    $row_info = $connection->get_monitor_info_by_id($monitor_id);
    $last_log = $connection->get_last_log_by_monitor_id($monitor_id);

    if ($row_info['m_is_obsolete'] == "1" || $row_info['u_id'] != get_user_no()) {
        go_to_page("/monitoring");
        return false;
    }
?>
<!DOCTYPE html>
<html lang="<?= get_locale() ?>">
	<head>
		<meta charset="<?= get_charset(SET_DEFAULT) ?>">
		<?= load_style_common() ?>
        <?= load_style_tui_chart() ?>
        <?= load_style_datatables() ?>
        <?= load_style_dropzone() ?>
		<?= get_meta_common() ?>
		<?= highlight_menu('monitoring') ?>
		<title><?=$row_info['m_name']?> 정보<?= get_site_title() ?></title>
		<meta name="description" content="<?=$row_info['m_desc']?>">
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
            <div class="b-box-black">
                <div class="ui relaxed divided list">
                    <div class="item">
                        <i class="large desktop middle aligned icon"></i>
                        <div class="content">
                        <h4 class="header">Operation System</h4>
                        <div class="description"><?=$last_log['l_os_name']?></div>
                        </div>
                    </div>
                    <div class="item">
                        <i class="large server middle aligned icon"></i>
                        <div class="content">
                        <h4 class="header">Hostname</h4>
                        <div class="description"><?=$last_log['l_host_name']?></div>
                        </div>
                    </div>
                    <div class="item">
                        <i class="large clock middle aligned icon"></i>
                        <div class="content">
                        <h4 class="header">Last modified</h4>
                        <div class="description"><?=$last_log['l_timestamp']?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="ping-message-box" class="ui icon message">
                <i id="ping-message-icon" class="notched circle loading icon"></i>
                <div class="content">
                    <div id="ping-message-title" class="header">
                    서버가 응답하는지 확인하는 중입니다... (80번 포트)
                    </div>
                    <p id="ping-result">잠시 후 결과가 나타납니다.</p>
                </div>
            </div>
            <div class="b-box-black">
                <div class="ui three stackable doubling cards mar-1y" id="sort-grid">
                    <?php
                        print($connection->get_last_log_graph($monitor_id));
                    ?>
                </div>
            </div>
            <div class="b-box-black">
                <table id="recent-log-table" class="display nowrap ui celled table" style="width:100%">
                    <thead>
                        <tr>
                            <th data-priority="1">No.</th>
                            <th>CPU USE</th>
                            <th>CPU SYS</th>
                            <th>MEM Use</th>
                            <th>MEM Avail</th>
                            <th>DISK Use</th>
                            <th>DISK Avail</th>
                            <th>RX Byte</th>
                            <th>TX Byte</th>
                            <th>RX Packets</th>
                            <th>TX Packets</th>
                            <th data-priority="1">Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            print($connection->get_log_monitor_by_id($monitor_id));
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="b-box-black">
                <h2><i class="window maximize outline icon"></i>CPU usage Top 5</h2>
                <?php
                    print($connection->get_last_top_process("cpu", $monitor_id));
                ?>
            </div>
            <div class="b-box-black">
                <h2><i class="microchip icon"></i>Memory usage Top 5</h2>
                <?php
                    print($connection->get_last_top_process("mem", $monitor_id));
                ?>
            </div>
            <div class="b-box-black">
                <h2><i class="database icon"></i>수동으로 로그 수집</h2>
                <div id="uri-clipboard-message" class="ui green message hidden">
                    <i class="copy icon"></i>
                    클립보드에 복사되었습니다.
                </div>
                <div class="ui placeholder segment">
                    <div class="ui two column stackable center aligned grid">
                        <div class="ui vertical divider">OR</div>
                        <div class="middle aligned row">
                            <div class="column">
                                <div class="ui icon header">
                                    <i class="linkify icon"></i>
                                    아래 URI로 로그 전송
                                </div>
                                <p id="uri-area" class="pad-5x"><?= "https://api.overlog.io/collect/monitor/" . $row_info['m_token'] ?></p>
                                <div class="field">
                                    <input id="clipboard-area" type="text" value="" style="position:absolute;top:-9999em">
                                    <div class="ui buttons">
                                        <button id="btn-copy-to-clipboard" class="ui primary button">링크 복사</button>
                                        <a href="../help/" class="ui button">수집 방법</a>
                                    </div>
                                </div>
                            </div>
                            <div class="column">
                                <form method="multipart/form-data" id="file-form">
                                    <input name="token" type="hidden" value="<?=$row_info['m_token']?>">
                                    <div class="ui icon header">
                                        <i class="cloud upload icon"></i>
                                        로그파일 업로드
                                    </div>
                                    <div id="fileDropzone" class="dropzone needsclick dz-clickable">
                                        <div class="dz-message needsclick">
                                            파일을 드래그하거나 여기를 클릭하여 업로드합니다.
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		</section>
	</body>
	<?= load_script_jquery() ?>
    <?= load_script_semantic() ?>
    <?= load_script_tui_chart() ?>
    <?= load_script_app_chart() ?>
    <?= load_script_datatables() ?>
    <?= load_script_dropzone() ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#recent-log-table').DataTable({
                responsive: true,
                "language": {
                    "sEmptyTable":     "데이터가 없습니다",
                    "sInfo":           "_START_ - _END_ / _TOTAL_",
                    "sInfoEmpty":      "0 - 0 / 0",
                    "sInfoFiltered":   "(총 _MAX_ 개)",
                    "sInfoPostFix":    "",
                    "sInfoThousands":  ",",
                    "sLengthMenu":     "페이지당 줄수 _MENU_",
                    "sLoadingRecords": "읽는중...",
                    "sProcessing":     "처리중...",
                    "sSearch":         "검색:",
                    "sZeroRecords":    "검색 결과가 없습니다",
                    "oPaginate": {
                        "sFirst":    "처음",
                        "sLast":     "마지막",
                        "sNext":     "다음",
                        "sPrevious": "이전"
                    },
                    "oAria": {
                        "sSortAscending":  ": 오름차순 정렬",
                        "sSortDescending": ": 내림차순 정렬"
                    }
                }
            }).column('0:visible').order('desc').draw();

            $.ajax({
                type: 'GET',
                url: '../api/get/ping',
                data : {
                    "host": "<?=$row_info['m_host_ip']?>"
                },
                contentType: 'html',
                success: function(data) {
                    
                    $("#ping-message-icon").removeClass("notched circle loading");
                    
                    if (data != -1) {
                        $("#ping-message-icon").addClass("check circle");
                        $("#ping-message-box").addClass("positive");
                        $("#ping-message-title").text("서버가 정상적으로 응답하고 있습니다.");
                        $("#ping-result").text("응답시간: " + data + ".ms");
                    }
                    else {
                        $("#ping-message-icon").addClass("exclamation circle");
                        $("#ping-message-box").addClass("negative");
                        $("#ping-message-title").text("서버 상태를 확인할 수 없음");
                        $("#ping-result").text("80번 포트 기준 서버 응답이 지연되고 있습니다.");
                    }
                }
            });
        });

        Dropzone.options.fileDropzone = {
            url: './file-upload',
            uploadMultiple: false,
            maxFilesize: 10,
            acceptedFiles: 'application/json, text/plain',
            addRemoveLinks: false,
            init: function() {
                var fileDropzone = this;

                // Append all the additional input data of your form here!
                this.on("sending", function(file, xhr, formData) {
                    formData.append("token", $("input[name=token]").val());
                });

                // Uploaded images
                this.on("addedfile", function(file) {
                    if (checkForm()) {
                        if (fileDropzone.getQueuedFiles().length > 0)
                            fileDropzone.processQueue();
                    }
                });

                this.on("success", function(file) {
                });
            }
        };

        function checkForm() {
            console.log("checkForm");
            return true;
        }

        $("#btn-copy-to-clipboard").click(function() {
            $("#clipboard-area").val($("#uri-area").text());
            $("#clipboard-area").select();
            document.execCommand("Copy");
            $("#uri-clipboard-message").removeClass("hidden");
        });
    </script>
</html>
<?php
	get_page('/include/footer');
?>
