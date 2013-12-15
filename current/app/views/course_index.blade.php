@extends('layouts.default')

@section('css')
	{{ HTML::style('css/table/table_style_1.css') }}
	{{ HTML::style('css/form/course_form.css') }}
	{{ HTML::style('css/course_index.css') }}
@stop

@section('content')
	<h1>課程管理</h1>

	{{ HtmlComposite::messageBlock() }}

	{{ FormList::open('' , URL::to('course/add')) }}
		{{ FormList::text('course_name', '課程名稱', array('required' => 'required', 'autofocus' => 'autofocus')) }}
		{{ FormList::submit('新增') }}
	{{ FormList::close() }}

	@if (isset($courseList))
		<div id="course_form">
	    	@foreach ($courseList as $course)
	    		{{ Form::open(array('url' => URL::to('course/edit/' . $course->course_id))) }}
    				<table class="data_table table_style_1">
	    				<tr>
	    					<td class="course_name">{{ Form::text('course_name', $course->course_name, array('required' => 'required')) }}</td>
	    					<td class="course_command">{{ Form::submit('更新') . '&nbsp;&nbsp;' . HtmlComposite::delete('course/delete/' . $course->course_id) }}</td>
	    				</tr>
	    			</table>
	    		{{ Form::close() }}
	    	@endforeach
		</div>
	@endif
@stop