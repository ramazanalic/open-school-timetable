@extends('layouts.default')

@section('css')
	{{ HTML::style('css/table/table_style_1.css') }}
	{{ HTML::style('css/form/classroom_form.css') }}
@stop

@section('content')
	<h1>教室管理</h1>

	<?php View::share('titlePrefix', '教室管理'); ?>
	
	{{ HtmlComposite::messageBlock() }}

	{{ FormList::open('' , URL::to('classroom/add')) }}
		{{ FormList::text('classroom_name', '教室名稱', array('required' => 'required', 'autofocus' => 'autofocus')) }}
		&nbsp;&nbsp;		
		{{ FormList::select('max_course', '同時使用班級數', array('range' => array(1, 5))) }}
		{{ FormList::submit('新增') }}
	{{ FormList::close() }}

	@if (isset($classroomList))
		<div id="classroom_form">
	    	@foreach ($classroomList as $classroom)
	    		{{ Form::open(array('url' => URL::to('classroom/edit/' . $classroom->classroom_id))) }}
    				<table class="data_table table_style_1">
	    				<tr>
	    					<td class="classroom_name">
	    						{{ Form::text('classroom_name', $classroom->classroom_name, array('required' => 'required')) }}
	    						{{ Form::selectRange('max_course', 1, 5, $classroom->max_course, array('required' => 'required')) }}
	    					</td>
	    					<td class="classroom_command">{{ Form::submit('更新') . '&nbsp;&nbsp;' . HtmlComposite::delete('classroom/delete/' . $classroom->classroom_id) }}</td>
	    				</tr>
	    			</table>
	    		{{ Form::close() }}
	    	@endforeach
		</div>
	@endif
@stop