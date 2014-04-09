@extends('layouts.default')

@section('css')	
	{{ HTML::style('css/form/caculate_form.css') }}
	{{ HTML::style('css/course_index.css') }}
@stop

@section('js')
	{{ HTML::script('js/extinction_toggle.js') }}
@stop

<?php View::share('titlePrefix', '計算課表'); ?>

@section('content')
	<h1>計算課表</h1>
	
	{{ HtmlComposite::messageBlock() }}

	{{ FormList::open('' , URL::to('caculate')) }}		
		{{ FormList::select('time', '計算複雜度：', array('range' => array(1, 5))) }}
		<br>
		{{ FormList::checkbox('extinction', '使用粒子活化機制', 1) }}		
		{{ FormList::select('extinction_time', '活化程度：', array('range' => array(1, 5))) }}
		<br>
		{{ FormList::submit('計算') }}
	{{ FormList::close() }}	
@stop