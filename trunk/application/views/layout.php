<!DOCTYPE html>
<html lang="zh">
	<head>
		<meta charset="utf-8" />
		<meta name="keywords" content="排課, 國小, 課表" />
		<meta name="description" content="國小排課系統" />
		<?= link_tag('css/YUI CSS Rest.css') ?>
		<?= link_tag('css/global.css') ?>
		<?php
			if (isset($css)) {
				foreach ($css as $cssfile) {
					echo link_tag('css/' . $cssfile . '.css'); 
				}
			}
		?>
        <title>OST排課系統<?= $layout_title ?></title>
    </head>
	<body>
		<div id="wrapper">
			<header>
				<div id="banner">
					OST排課系統
				</div>
				<div id="login">
					<a href="login">登入</a>
				</div>
			</header>
	    	
	    	<nav>
	         	<ul id="user_nav">
	                <li><a href="">班級課表</a></li>
	                <li><a href="">教師課表</a></li>
	                <li><a href="">教室課表</a></li>
	            </ul>
				<ul id="teacher_nav">
	                <li><a href="">個人排課需求設定</a></li>
	            </ul>
				<ul id="admin_nav">
	                <li><a href="">教師排課限制設定</a></li>
					<li><a href="">課程排課需求設定</a></li>
					<li><a href="">課程排課限制設定</a></li>
					<li><a href="<?= base_url() . 'account'?>">帳號密碼管理</a></li>
	            </ul>
	    	</nav>
	
	        <section>
	        	<?= $layout_content ?>
	        </section>	        
        </div>
        
        <footer>
			<a href="http://code.google.com/p/open-school-timetable/">程式專案網站</a>
			
			系統介面參考Micriosoft Windows 8 Morden UI
        </footer>
        
        <script src="<?= base_url() . 'javascript/jQuery.js' ?>" type="text/javascript"></script>
	</body>
</html>