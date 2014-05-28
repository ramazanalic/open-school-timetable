@extends('layouts.default')

@section('css')
	{{ HTML::style('css/form/teacher_require.css') }}
	{{ HTML::style('css/js/course_time_selector.css') }}
@stop

@section('js')
	{{ HTML::script('js/course_time_selector.js') }}	
@stop

<?php View::share('titlePrefix', '設定《' . Auth::user()->teacher_name . '》的排課需求') ?>
<?php View::share('selectUrl', 'teacher_require'); ?>

@section('content')			
	<h1>{{ '設定《' . Auth::user()->teacher_name . '》的排課需求' }}</h1>

	{{ Helper::message() }}

	{{ FormList::open(Auth::user(), 'teacher_require/' . Auth::user()->teacher_id) }}	
	{{ FormList::hidden('course_time') }}	
	<br>		
	<li id="teacher_course_time">			
		@include('course_time_selector', array('course_time' => Auth::user()->course_time))
	</li>
	<br>
	{{ FormList::submit('更新') }}
	{{ FormList::close() }}
@stop
