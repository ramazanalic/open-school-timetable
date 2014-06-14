@extends('layouts.default')

@section('css')
	{{ HTML::style('css/table/style_2.css') }}
	{{ HTML::style('css/form/course.css') }}
	{{ HTML::style('css/course.css') }}
@stop

<?php View::share('titlePrefix', '課程管理'); ?>
<?php View::share('selectUrl', 'course'); ?>

@section('content')
	<h1>課程管理</h1>
	
	{{ Helper::message() }}

	{{ FormList::open('' , 'course/add') }}
		{{ FormList::text('course_name', '課程名稱', array('required' => 'required', 'autofocus' => 'autofocus')) }}
		{{ FormList::submit('新增') }}
	{{ FormList::close() }}

	@if (isset($courseList))
		<div id="course_form">
			<table class="data_table table_style_2">
				<tr>
    				<th class="course_name">課程名稱</td>
    				<th class="course_command"></th>
    			</tr>    			
    		</table>
	    	@foreach ($courseList as $course)
	    		{{ Form::open(array('url' => 'course/edit/' . $course->course_id)) }}
    				<table class="data_table table_style_2">    					
	    				<tr>
	    					<td class="course_name">{{ Form::text('course_name', $course->course_name, array('required' => 'required')) }}</td>
	    					<td class="course_command">{{ Form::submit('更新') . '&nbsp;&nbsp;' . Helper::delete('course/delete/' . $course->course_id) }}</td>
	    				</tr>
	    			</table>
	    		{{ Form::close() }}
	    	@endforeach
		</div>
	@endif
@stop