@extends('layouts.default')

@section('css')
	{{ HTML::style('css/form/change_password.css') }}	
@stop

<?php View::share('titlePrefix', '變更《' . Auth::user()->teacher_name . '》的密碼') ?>

@section('content')			
	<h1>{{ '變更《' . Auth::user()->teacher_name . '》的密碼' }}</h1>

	{{ HtmlComposite::messageBlock() }}

	{{ FormList::open(Auth::user(), 'change_password/' . Auth::user()->teacher_id) }}		
	{{ FormList::description('請輸入舊密碼、新密碼') }}
	<br>
	{{ FormList::password('old_teacher_password', '舊密碼') }}			
	<br>
	{{ FormList::password('teacher_password', '新密碼') }}
	{{ FormList::password('teacher_password_confirmation', '確認密碼') }}
	<br>
	{{ FormList::submit('變更密碼') }}
	{{ FormList::close() }}
@stop
