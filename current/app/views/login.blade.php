@extends('layouts.default')

@section('css')
	{{ HTML::style('css/form/login.css') }}
@stop

<?php View::share('titlePrefix', '登入'); ?>
<?php View::share('selectUrl', 'login'); ?>

@section('content')	
	<h1>登入</h1>
	
	{{ Helper::message() }}
	
	{{ FormList::open('', 'login') }}
	{{ FormList::text('teacher_account', '帳號', array('required' => 'required', 'autofocus' => 'autofocus')) }}
	{{ FormList::password('teacher_password', '密碼', array('required' => 'required')) }}
	{{ FormList::submit('登入') }}
	{{ FormList::close() }}
@stop
