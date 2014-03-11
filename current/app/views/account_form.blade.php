@extends('layouts.default')

@section('css')
	{{ HTML::style('css/form/account_form.css') }}
	{{ HTML::style('css/js/course_time_selector.css') }}
@stop

@section('js')
	{{ HTML::script('js/course_time_selector.js') }}	
@stop

<?php View::share('titlePrefix', (isset($teacher)) ? '編輯帳號《' . $teacher->teacher_name . '》' : '新增帳號') ?>

@section('content')		
	{{ HtmlComposite::back('account') }}

	<h1>{{ (isset($teacher)) ? '編輯帳號《' . $teacher->teacher_name . '》' : '新增帳號'}}</h1>

	{{ HtmlComposite::messageBlock() }}

	{{ FormList::open($teacher, (isset($teacher)) ? 'account/edit/' . $teacher->teacher_id . '/titleId/' . $titleId: 'account/add') }}
	{{ FormList::description('請輸入以下的資料') }}
	{{ FormList::hidden('course_time') }}	
	<br>	
	{{ FormList::text('teacher_name', '教師姓名', array('autofocus' => 'autofocus', 'required' => 'required')) }}
	{{ FormList::text('teacher_account', '帳號', array('placeholder' => '英文+數字', 'required' => 'required')) }}	
	{{ FormList::select('teacher_privilege', '權限', array('valueArray' => array('16' => '一般使用者', '2' => '管理者'), 'required' => 'required')) }}
	<li id="teacher_course_time">	
		教師排課需求設定
		@include('course_time_selector', array('course_time' => (isset($teacher) ? $teacher->course_time : '')))
	</li>
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
@stop
