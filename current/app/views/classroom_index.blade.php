@extends('layouts.default')

@section('css')
	{{ HTML::style('css/table/table_style_1.css') }}
	{{ HTML::style('css/form/classroom_form.css') }}
	{{ HTML::style('css/js/course_time_selector.css') }}
@stop

@section('js')
	{{ HTML::script('js/course_time_selector.js') }}	
@stop

<?php View::share('titlePrefix', '教室管理'); ?>

@section('content')
	<h1>教室管理</h1>	
	
	{{ HtmlComposite::messageBlock() }}

	{{ FormList::open($classroom, URL::to(isset($classroom) ? 'classroom/edit/' . $classroom->classroom_id : 'classroom/add')) }}	
		{{ FormList::text('classroom_name', '教室名稱', array('required' => 'required', 'autofocus' => 'autofocus')) }}
		&nbsp;&nbsp;		
		{{ FormList::select('count', '同時使用班級數', array('range' => array(1, 10))) }}
		{{ Form::hidden('course_time') }}
		{{ FormList::submit('新增') }}
	{{ FormList::close() }}

	@if (isset($classroomList))
		<div id="classroom_form">
	    	@foreach ($classroomList as $classroom)	    		
				<table class="data_table table_style_1">
    				<tr>
    					<td class="classroom_name">
    						{{ $classroom->classroom_name }}
    						{{ $classroom->count }}    						
    					</td>
    					<td class="classroom_command">{{ HtmlComposite::delete('classroom/delete/' . $classroom->classroom_id) }}</td>
    				</tr>
    			</table>	    		
	    	@endforeach
		</div>
	@endif
	
	<div id="teacher_course_time">			
		@include('course_time_selector', array('course_time' => $classroom->course_time))
	</div>
@stop