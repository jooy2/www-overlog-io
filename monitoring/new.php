<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
	get_page('/include/header');
    authentication_check(SITE_HOME . "/monitoring/new");
    
    use_database();
    $connection = ConnectDB::getInstance();

    if (get_method() == "post" 
        && isset($_POST['dev-name'])
        && isset($_POST['dev-desc'])
        && isset($_POST['dev-hostname'])
        && isset($_POST['dev-ip'])) {
        $name = addslashes($_POST['dev-name']);
        $desc = addslashes($_POST['dev-desc']);
        $host_name = addslashes($_POST['dev-hostname']);
        $host_ip = addslashes($_POST['dev-ip']);
        $os = addslashes($_POST['dev-os']);
        $type = addslashes($_POST['dev-type']);
        $icon = addslashes($_POST['dev-icon']);
        $token = addslashes($_POST['dev-token']);

        if ($connection->set_dashboard_data(get_user_no(), $name, $desc, $host_name,
                                                $host_ip, $os, $type, $icon, $token)) {
            go_to_page("/monitoring");
            return false;
        } else {
            echo "<div class='mar-7y b-box-black'>알 수 없는 에러가 발생했습니다. 다시 생성해야 합니다!</div>";
            return false;
        }
    }

    $token = bin2hex(openssl_random_pseudo_bytes(16));
?>
<!DOCTYPE html>
<html lang="<?= get_locale() ?>">
	<head>
		<meta charset="<?= get_charset(SET_DEFAULT) ?>">
		<?= load_style_common() ?>
		<?= get_meta_common() ?>
		<?= highlight_menu('monitoring') ?>
		<title>모니터링 생성<?= get_site_title() ?></title>
		<meta name="description" content="">
	</head>
	<body class="no-select">
		<section class="ui container">
            <div class="ui ordered steps tablet computer only grid">
                <div id="step-1" class="active step">
                    <div class="content">
                        <div class="title">운영체제 선택</div>
                        <div class="description">장치 운영체제 확인</div>
                        <div class="selected-value"></div>
                        <div class="message hidden">이 데이터를 받는 장치의 운영체제는 무엇입니까?</div>
                    </div>
                </div>
                <div id="step-2" class="active step">
                    <div class="content">
                        <div class="title">데이터 형식</div>
                        <div class="description">수신받는 데이터 타입 선택</div>
                        <div class="selected-value"></div>
                        <div class="message hidden">수신받는 데이터가 어떠한 포맷을 사용하는지 선택합니다.</div>
                    </div>
                </div>
                <div id="step-3" class="active step">
                    <div class="content">
                        <div class="title">정보 입력</div>
                        <div class="description">모니터링 장치 정보</div>
                        <div class="selected-value"></div>
                        <div class="message hidden">이 데이터를 받는 장치의 정보를 입력합니다.</div>
                    </div>
                </div>
                <div id="step-4" class="active step">
                    <div class="content">
                        <div class="title">검토</div>
                        <div class="description">수집 링크 확인</div>
                        <div class="selected-value"></div>
                        <div class="message hidden">다음 수집 링크로 POST 데이터를 보내면 데이터가 수집될 것입니다. 나중에 다시 확인할 수 있습니다.</div>
                    </div>
                </div>
            </div>
            <div id="message-area" class="ui message">
                <h3 id="ui-title"></h3>
                <p id="ui-message"></p>
            </div>
            <div id="step-1-panel" class="ui grid cards middle aligned">
                <?php
                    print($connection->get_select_card('operation', 'data_log_os', false, null));
                ?>
            </div>
            <div id="step-2-panel" class="ui grid cards middle aligned hidden">
                <?php
                    print($connection->get_select_card('type', 'data_log_type', true, 'monitoring'));
                ?>
            </div>
            <div id="step-3-panel" class="ui grid middle aligned hidden b-box-black">
                <div id="form-error-message" class="ui red message hidden mar-3x mar-3y">
                    <i class="copy icon"></i>이름, 설명, IP는 비어있으면 안됩니다!
                </div>
                <form class="ui container no-margin pad-2y" id="data-form" method="post" action="new">
                    <div class="ui fluid labeled input">
                        <div class="ui label">
                            장치 이름
                        </div>
                        <input id="dev-name" name="dev-name" type="text" maxlength="15" placeholder="이름">
                    </div>
                    <div class="ui fluid labeled input">
                        <div class="ui label">
                            장치 설명
                        </div>
                        <input id="dev-desc" name="dev-desc" type="text" maxlength="100" placeholder="설명">
                    </div>
                    <div class="ui fluid labeled input">
                        <div class="ui label">
                            호스트 주소 (존재하는 경우)
                        </div>
                        <input id="dev-hostname" name="dev-hostname" maxlength="40" type="text" placeholder="호스트명">
                    </div>
                    <div class="ui fluid labeled input">
                        <div class="ui label">
                            호스트 IP
                        </div>
                        <input id="dev-ip" name="dev-ip" maxlength="20" type="text" placeholder="호스트 IP">
                    </div>
                    <input id="step-1-form-value" name="dev-os" type="hidden">
                    <input id="step-2-form-value" name="dev-type" type="hidden">
                    <input id="step-3-form-icon" name="dev-icon" type="hidden">
                    <input id="step-4-form-token" name="dev-token" type="hidden">
                </form>
                <div id="data-form-submit" class="ui blue button mar-2x mar-2y">입력 완료</div>
            </div>
            <div id="step-4-panel" class="ui middle aligned hidden">
                <div class="ui b-box-black">
                    <h3 id="uri-area"><?= SITE_HOME . "/api/collect/monitor/" . $token ?></h3>
                    <input id="clipboard-area" type="text" value="" style="position:absolute;top:-9999em">
                    <div id="btn-copy-to-clipboard" class="ui button"><i class="clipboard outline icon"></i>클립보드로 복사</div>
                    <div id="uri-clipboard-message" class="ui green message hidden"><i class="copy icon"></i>클립보드에 복사되었습니다.</div>
                </div>
                <div class="ui b-box-black">
                    <h3>확인이 완료되었다면, 저장합니다.</h3>
                    <div id="btn-submit" class="ui button blue">만들기</div>
                    <div id="btn-reset" class="ui button red">초기화</div>
                </div>
                <div class="ui b-box-black">
                    <h3>다음과 같이 데이터가 수집되어야 합니다.</h3>
                    <p>(아래는 예시 데이터입니다.)</p>
                    <pre id="example-data-1 hidden">
WINDOWS
MOBILEJ0506-19
==cpu values==
us0.6,sy1.1,id98.3
==cpu top5==
**7.5,/explorer
**3,/WindowsFormsApp1
**3,/WmiPrvSE
**3,/WmiPrvSE
**0.8,/dwm
==mem values==
total16716800,free11396040,used4660352,buff/cache660408
==swap values==
total38690187,free33433623,used.5256564,avail Memnot supported
==mem top5==
**5.4,/devenv
**4.5,/ServiceHub.DataWarehouseHost
**3.6,/chrome
**3.3,/chrome
**3.3,/chrome
=network traffic=
RXbyte149552329,RXpacketsnot supported
TXbyte10220530,TXpacketsnot supported
==disk total==
1k-block1252543464,us984098804
==disk other==
C:\,1k-block244094972,us73685024
D:\,1k-block976761852,us894058212
F:\,1k-block31686640,us16355568
                    </pre>
                </div>
            </div>
            <div id="step-5-panel" class="ui middle aligned hidden">
                <div class="ui b-box-black">
                    <h3><i class="notched circle loading icon"></i>잠시만 기다려주세요...</h3>
                </div>
            </div><!-- step 5 -->
		</section>
	</body>
    <script type="text/javascript">
        $(document).ready(function() {
            getUIContent(1);
        });

        // Step 1
        $('.card').on("click", ".data-operation-select", function() {
            completeStep(1);
            setValueForStep(1, $(this).data('operation-id'),
                $(this).data('name'), $(this).data('icon'));
        });
        
        // Step 2
        $('.card').on("click", ".data-type-select", function() {
            completeStep(2);
            setValueForStep(2, $(this).data('type-id'),
                $(this).data('name'), $(this).data('icon'));
        });

        // Step 3
        $('#data-form-submit').click(function() {
            // Validate check
            $('#form-error-message').addClass("hidden");

            if (formLengthCheck('#dev-name', 2)) {
                $('#form-error-message').removeClass("hidden");
                return false;
            }

            if (formLengthCheck('#dev-ip', 7)) {
                $('#form-error-message').removeClass("hidden");
                return false;
            }
        
            $("#step-3-form-icon").val("windows");
            completeStep(3);
            setValueForStep(3, 0,
                $('#dev-name').val(), 'check circle');
            $('#example-data-' + $('#step-1').find('selected-value').html()).removeClass("hidden");
        });

        // Step 3 - form
        $('#dev-name').on('change', function() {
			$('#dev-name').val(filterSpecialChar($('#dev-name').val()));
        });

        // Step 4
        $("#btn-submit").click(function() {
            $("#step-4-form-token").val("<?=$token?>");

            completeStep(4);
            setValueForStep(4, 0,
                '완료', 'check circle');
            $("#message-area").addClass("hidden");
            $("#step-5-panel").removeClass("hidden");
            setTimeout(function() {
                $("#data-form").submit();
            }, 2000);
        });

        $("#btn-reset").click(function() {
            location.reload();
        });

        $("#btn-copy-to-clipboard").click(function() {
            $("#clipboard-area").val($("#uri-area").text());
            $("#clipboard-area").select();
            document.execCommand("Copy");
            $("#uri-clipboard-message").removeClass("hidden");
        });
    </script>
    <?= load_script_jquery() ?>
    <?= load_script_common() ?>
	<?= load_script_semantic() ?>
    <?= load_script_analytics() ?>
</html>
<?php
	get_page('/include/footer');
?>