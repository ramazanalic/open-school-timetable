<!DOCTYPE html>
<html lang="zh">
	<head>
		<meta charset="utf-8" />
		<meta name="keywords" content="排課, 國小, 課表">
		<meta name="description" content="國小排課系統">
		<link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
		{{ HTML::style('css/global/yui_cssreset-min.css') }}
		{{ HTML::style('css/global/default.css') }}
		@yield('css')
		<title>{{ (isset($titlePrefix)) ? $titlePrefix . ' - ' : ''}}OST排課系統</title>
	</head>
	<body>
		<header>
			<div id="banner">				
				<a href="{{ URL::to('/') }}">{{ HTML::image(asset('image/ost_icon.png'), 'Icon'). 'OST排課系統' }}</a>
			</div>
			<div id="login">
				@if (Auth::check())
					<span class="info">
						{{ Auth::user()->teacher_name }}（{{ (Auth::user()->teacher_privilege == 2) ? '管理者' : '一般使用者' }}）					
					</span>
					｜
					{{ link_to('logout', '登出') }}
				@else
					{{ link_to('login', '登入') }}
				@endif
			</div>
		</header>

		<main>
			<nav>
				<ul id="user_nav">
					<li class="nav_title">
						課表查詢
					</li>
					<li>						
						{{ link_to('class_table', '班級課表', array('class' => ($selectUrl == 'class_table') ? 'selected' : '')) }}
					</li>
					<li>
						{{ link_to('teacher_table', '教師課表', array('class' => ($selectUrl == 'teacher_table') ? 'selected' : '')) }}
					</li>
					<li>
						{{ link_to('classroom_table', '教室課表', array('class' => ($selectUrl == 'classroom_table') ? 'selected' : '')) }}
					</li>
				</ul>
				
				@if (Auth::check())
					<ul id="teacher_nav">
						<li class="nav_title">
							教師設定
						</li>
						<li>						
							{{ link_to('teacher_require/' . Auth::user()->teacher_id, '排課需求設定', array('class' => ($selectUrl == 'teacher_require') ? 'selected' : '')) }}						
						</li>
						<li>
							{{ link_to('change_password/' . Auth::user()->teacher_id, '變更密碼',  array('class' => ($selectUrl == 'change_password') ? 'selected' : '')) }}							
						</li>
					</ul>
				@endif
				
				@if (Auth::check() && Auth::user()->teacher_privilege < 16)
					<ul id="admin_nav">
						<li class="nav_title">
							系統管理
						</li>					
						<li>						
							{{ link_to('timetable', '排課設定', array('class' => ($selectUrl == 'timetable') ? 'selected' : '')) }}
						</li>																				
						<li>
							{{ link_to('account', '帳號管理', array('class' => ($selectUrl == 'account') ? 'selected' : '')) }}
						</li>
						<li>
							{{ link_to('class_year', '班級、年級管理', array('class' => ($selectUrl == 'class_year') ? 'selected' : '')) }}
						</li>
						<li>
							{{ link_to('course', '課程管理', array('class' => ($selectUrl == 'course') ? 'selected' : '')) }}
						</li>
						<li>
							{{ link_to('classroom', '教室管理', array('class' => ($selectUrl == 'classroom') ? 'selected' : '')) }}
						</li>					
						<li>
							{{ link_to('caculate', '計算課表', array('class' => ($selectUrl == 'caculate') ? 'selected' : '')) }}
						</li>
					</ul>
				@endif
			</nav>

			<article>
				@yield('content')
			</article>
		</main>

		<footer>
			{{ link_to('http://code.google.com/p/open-school-timetable/', '程式專案網站') }}
		</footer>
		{{ HTML::script('js/jquery-2.1.1.min.js') }}
		@yield('js')
	</body>
</html>