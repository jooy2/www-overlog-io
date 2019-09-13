<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
?>
<!DOCTYPE html>
<html lang="<?= get_locale() ?>">
	<head>
		<meta charset="<?= get_charset(SET_DEFAULT) ?>">
		<?= load_style_semantic() ?>
		<?= load_style_common() ?>
		<?= get_meta_common() ?>
		<title>계정 생성이 완료됨<?= get_site_title() ?></title>
		<meta name="description" content="">
	</head>
	<body>
		<div class="ui middle aligned center aligned grid">
  			<div class="column">
    			<h2 class="ui image header">
      				<div class="content">
						환영합니다
      				</div>
    			</h2>
                <div class="ui message">
                    새로운 계정이 생성되어 이제 <a href="login">로그인</a>할 수 있습니다.
                </div>
            </div>
        </div>
	</body>
    <?= load_script_common() ?>
	<?= load_script_jquery() ?>
	<?= load_script_semantic() ?>
</html>