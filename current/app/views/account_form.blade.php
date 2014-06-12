@extends('layouts.default')

@section('css')
	{{ HTML::style('css/form/account.css') }}
	{{ HTML::style('css/js/course_time_selector.css') }}
	{{ HTML::style('css/column_item/style_1.css') }}
	{{ HTML::style('css/account_edit.css') }}
@stop

@section('js')
	{{ HTML::script('js/course_time_selector.js') }}	
@stop

<?php View::share('titlePrefix', (isset($teacher)) ? '編輯帳號《' . $teacher->teacher_name . '》' : '新增帳號') ?>
<?php View::share('selectUrl', 'account'); ?>

@section('content')		
	{{ Helper::back('account/view_title/' . $titleId) }}

	<h1>{{ (isset($teacher)) ? '編輯帳號《' . $teacher->teacher_name . '》' : '新增帳號'}}</h1>

	{{ Helper::message() }}

	{{ FormList::open($teacher, (isset($teacher)) ? 'account/edit/' . $teacher->teacher_id . '/titleId/' . $titleId : 'account/add/' . $titleId) }}
	{{ FormList::description('請輸入以下的資料') }}
	{{ FormList::hidden('course_time') }}	
	<br>	
	{{ FormList::text('teacher_name', '教師姓名', ['autofocus' => 'autofocus', 'required' => 'required']) }}
	{{ FormList::text('teacher_account', '帳號', ['placeholder' => '英文+數字', 'required' => 'required']) }}	
	{{ FormList::select('teacher_privilege', '權限', ['valueArray' => ['16' => '一般使用者', '2' => '管理者'], 'required' => 'required']) }}
	
	<br>
	{{ FormList::select('title_id', '職稱', array('valueArray' => array('0' => '無職稱'), 'required' => 'required'), array('Title', 'title_id', 'title_name')) }}
	{{ FormList::select('classes_id', '導師班', array('valueArray' => Classes::getClassesSelectArray(), 'required' => 'required')) }}
	{{ FormList::select('teacher_course_count', '上課節數', array('range' => array(1, 25))) }}	
	<br>
	{{ FormList::password('teacher_password', '密碼') }}
	{{ FormList::password('teacher_password_confirmation', '確認密碼') }}
	<br>
		
	<br>
	{{ (isset($teacher)) ? FormList::submit('更新') : FormList::submit('新增') }}
	{{ FormList::close() }}
	
	<div id="teacher_require">
		<strong>教師排課需求設定</strong>
		@include('course_time_selector', array('course_time' => (isset($teacher) ? $teacher->course_time : '')))	
	</div>
		
	@if (isset($teacherCourseunit) && count($teacherCourseunit) != 0)	
		<div id="teacher_courseunit" class="column_item column_item_style_1">
			<ul>
				<li class="title">
					{{ $teacher->teacher_name }}已設定排課
				</li>
				@foreach ($teacherCourseunit as $courseunit)
					<li>
						{{ Helper::edit('timetable/view_title/' . $titleId . '/' . $teacher->teacher_id . '/' . $courseunit->course_unit_id, $courseunit->classes->classes_name. '[' . $courseunit->course->course_name . '](' . $courseunit->count . '節)') }}						
					</li>			
				@endforeach
			</ul>
		</div>	
	@endif 
@stop
