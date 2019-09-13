<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
	get_page('/include/header');
    authentication_check(SITE_HOME . "/analytics/new");
    
    use_database();
    $connection = ConnectDB::getInstance();
?>
<!DOCTYPE html>
<html lang="<?= get_locale() ?>">
	<head>
		<meta charset="<?= get_charset(SET_DEFAULT) ?>">
		<?= load_style_common() ?>
		<?= get_meta_common() ?>
		<?= highlight_menu('analytics') ?>
		<title>분석 데이터 만들기<?= get_site_title() ?></title>
		<meta name="description" content="">
	</head>
	<body class="no-select">
		<section class="ui container">
            <h2 class="ui header pad-2y">
                <i class="server icon"></i>
                <div class="content">
                    새로운 데이터
                    <div class="sub header">분석하고 저장하기 위한 새로운 데이터를 추가합니다.</div>
                </div>
            </h2>
            <div class="ui ordered steps tablet computer only grid mar-4y">
                <div id="step-1" class="active step">
                    <div class="content">
                        <div class="title">선택하기</div>
                        <div class="description">데이터 형식 지정</div>
                        <div class="selected-value"></div>
                        <div class="message hidden">사용할 데이터 형식을 지정합니다. 아래는 현재 지원되는 형식입니다.</div>
                    </div>
                </div>
                <div id="step-2" class="active step">
                    <div class="content">
                        <div class="title">작업</div>
                        <div class="description">원하는 작업 선택</div>
                        <div class="selected-value"></div>
                        <div class="message hidden">이 데이터로 수행할 작업을 선택해주세요.</div>
                    </div>
                </div>
                <div id="step-3" class="active step">
                    <div class="content">
                        <div class="title">업로드</div>
                        <div class="description">업로드 방식 설정</div>
                        <div class="selected-value"></div>
                        <div class="message hidden">작업을 진행할 파일을 업로드하거나 업로드 링크를 만드세요.</div>
                    </div>
                </div>
            </div>
            <div class="ui message">
                <h3 id="ui-title"></h3>
                <p id="ui-message"></p>
            </div>
            <div id="step-1-panel" class="ui grid cards middle aligned">
                <?php
                    print($connection->get_select_card('type', 'data_log_type', 'analytics'));
                ?>
            </div>
            <div id="step-2-panel" class="ui grid cards middle aligned hidden">
                <?php
                    print($connection->get_select_card('task', 'data_log_task', null));
                ?>
            </div>
            <div id="step-3-panel" class="ui grid cards middle aligned hidden">
                <?php
                ?>
            </div>
		</section>
	</body>
    <script type="text/javascript">
        $(document).ready(function() {
            getUIContent(1);
        });

        // Step 1
        $('.card').on("click", ".data-type-select", function() {
            completeStep(1);
            setValueForStep(
                1,
                $(this).data('type-id'),
                $(this).data('name'),
                $(this).data('icon')
            );
        });
        
        // Step 2
        $('.card').on("click", ".data-task-select", function() {
            completeStep(2);
            setValueForStep(
                2,
                $(this).data('task-id'),
                $(this).data('name'),
                $(this).data('icon')
            );
        });
    </script>
	<?= load_script_jquery() ?>
	<?= load_script_semantic() ?>
    <?= load_script_analytics() ?>
</html>
<?php
	get_page('/include/footer');
?>