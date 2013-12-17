<!DOCTYPE html>
<html lang="zh">
	<head>
		<meta charset="utf-8" />
		<meta name="keywords" content="排課, 國小, 課表">
		<meta name="description" content="國小排課系統">
		{{ HTML::style('css/global/yui_cssreset-min.css') }}
		{{ HTML::style('css/global/default.css') }}
		@yield('css')
		<title>{{ (isset($titlePrefix)) ? $titlePrefix . ' - ' : ''}}OST排課系統</title>
	</head>
	<body>
		<header>
			<div id="banner">
				<a href="{{ URL::to('/') }}">{{ HTML::image('image/ost_icon.png', 'Icon'). 'OST排課系統' }}</a>
			</div>
			<div id="login">
				<!-- {{ HTML::link(URL::to('login'), '登入') }} -->
			</div>
		</header>

		<main>
			<nav>
				<ul id="user_nav">
					<li class="nav_title">
						課表查詢
					</li>
					<li>
						{{ HTML::link(URL::to('class_view'), '班級課表') }}
					</li>
					<li>
						{{ HTML::link(URL::to('teacher_view'), '教師課表') }}
					</li>
					<li>
						{{ HTML::link(URL::to('classroom_view'), '教室課表') }}
					</li>
				</ul>
				<ul id="teacher_nav">
					<li class="nav_title">
						個人排課設定
					</li>
					<li>
						<a href="">個人排課需求設定</a>
					</li>
				</ul>
				<ul id="admin_nav">
					<li class="nav_title">
						系統管理
					</li>
					<li>
						<a href="">排課設定</a>
					</li>
					<li>
						<a href="">教師排課限制設定</a>
					</li>
					<li>
						<a href="">教師排課需求設定</a>
					</li>
					<li>
						{{ HTML::link(URL::to('account'), '帳號管理') }}
					</li>
					<li>
						{{ HTML::link(URL::to('class_year'), '班級、年級管理') }}
					</li>
					<li>
						{{ HTML::link(URL::to('course'), '課程管理') }}
					</li>
					<li>
						{{ HTML::link(URL::to('classroom'), '教室管理') }}
					</li>
				</ul>
			</nav>

			<article>
				@yield('content')
			</article>
		</main>

		<footer>
			{{ HTML::link('http://code.google.com/p/open-school-timetable/', '程式專案網站') }}
		</footer>
		{{ HTML::script('js/jquery-2.0.3.min.js') }}
		@yield('js')
	</body>
</html>