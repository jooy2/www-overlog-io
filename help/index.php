<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
	get_page('/include/header');
	authentication_check(SITE_HOME . "/collection");
?>
<!DOCTYPE html>
<html lang="<?= get_locale() ?>">
	<head>
		<meta charset="<?= get_charset(SET_DEFAULT) ?>">
		<?= load_style_common() ?>
		<?= get_meta_common() ?>
		<?= highlight_menu('help') ?>
		<title>도움말<?= get_site_title() ?></title>
		<meta name="description" content="">
	</head>
	<body class="no-select">
		<section class="ui container">
            <h2 class="ui header pad-2y">
                <i class="question circle icon"></i>
                <div class="content">
                도움말
                    <div id="dashboard-counter" class="sub header">여러가지 지침을 확인할 수 있습니다.</div>
                </div>
            </h2>
			<div class="b-box-black">
                <img src="<?=get_logo("overlog-logo-horizontal")?>">
				<p>준비중</p>
            </div>
            <div class="b-box-black">
                <h1>Capstone Project</h1>
                <p>이 프로젝트는 2019 <a target="_blank" href="http://daelim.ac.kr">대림대학교</a> 모바일인터넷과 캡스톤디자인에 의해 제작되었습니다.</p>
                <h3>프로젝트 기간</h3>
                <p>2019.09.01 ~ 2019.09.30</p>
                <h3>프로젝트 기여자</h3>
                <ul>
                    <li>이주연 (leejooy96@gmail.com)</li>
                    <li>이준오 (ljun50327@gmail.com)</li>
                    <li>손범준 (qjaens2321@gmail.com)</li>
                    <li>오윤기 (gkdldn233@naver.com)</li>
                </ul>
            </div>
			<div class="b-box-black">
				<h2>LICENSE</h2>
				<h3>Semantic UI by Semantic-Org</h3>
				<p>
				MIT License
				<br>
				<br>Copyright (c) 2018 Semantic-Org <a href="mailto:jack@semantic-ui.com">jack@semantic-ui.com</a>
				<br>Permission is hereby granted, free of charge, to any person obtaining a copy
				<br>of this software and associated documentation files (the "Software"), to deal
				<br>in the Software without restriction, including without limitation the rights
				<br>to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
				<br>copies of the Software, and to permit persons to whom the Software is
				<br>furnished to do so, subject to the following conditions:
				<br>
				<br>The above copyright notice and this permission notice shall be included in all
				<br>copies or substantial portions of the Software.
				<br>
				<br>THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
				<br>IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
				<br>FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
				<br>AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
				<br>LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
				<br>OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
				<br>SOFTWARE.
				</p>

				<h3>TOAST UI Chart by NHN</h3>
				<p>
				MIT License
				<br>
				<br>Copyright (c) 2019 NHN Corp.
				<br>
				<br>Permission is hereby granted, free of charge, to any person obtaining a copy
				<br>of this software and associated documentation files (the "Software"), to deal
				<br>in the Software without restriction, including without limitation the rights
				<br>to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
				<br>copies of the Software, and to permit persons to whom the Software is
				<br>furnished to do so, subject to the following conditions:
				<br>
				<br>The above copyright notice and this permission notice shall be included in
				<br>all copies or substantial portions of the Software.
				<br>
				<br>THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
				<br>IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
				<br>FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
				<br>AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
				<br>LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
				<br>OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
				<br>THE SOFTWARE.
				</p>

				<h3>Pushbar by Biraj Ghosh</h3>
				<p>
				MIT License
				<br>
				<br>Copyright (c) 2018 Biraj Ghosh <a href="mailto:biraj@oncebot.com">biraj@oncebot.com</a>
				<br>Permission is hereby granted, free of charge, to any person obtaining a copy
				<br>of this software and associated documentation files (the "Software"), to deal
				<br>in the Software without restriction, including without limitation the rights
				<br>to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
				<br>copies of the Software, and to permit persons to whom the Software is
				<br>furnished to do so, subject to the following conditions:
				<br>
				<br>The above copyright notice and this permission notice shall be included in all
				<br>copies or substantial portions of the Software.
				<br>
				<br>THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
				<br>IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
				<br>FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
				<br>AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
				<br>LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
				<br>OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
				<br>SOFTWARE.
                </p>
                
                <h3>Sortable by Sortable</h3>
				<p>
				MIT License
				<br>
				<br>Copyright (c) 2019 All contributors to Sortable
				<br>Permission is hereby granted, free of charge, to any person obtaining a copy
				<br>of this software and associated documentation files (the "Software"), to deal
				<br>in the Software without restriction, including without limitation the rights
				<br>to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
				<br>copies of the Software, and to permit persons to whom the Software is
				<br>furnished to do so, subject to the following conditions:
				<br>
				<br>The above copyright notice and this permission notice shall be included in all
				<br>copies or substantial portions of the Software.
				<br>
				<br>THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
				<br>IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
				<br>FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
				<br>AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
				<br>LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
				<br>OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
				<br>SOFTWARE.
                </p>
			</div>
		</section>
	</body>
	<?= load_script_jquery() ?>
	<?= load_script_semantic() ?>
</html>
<?php
	get_page('/include/footer');
?>
