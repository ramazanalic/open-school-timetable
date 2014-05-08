@extends('layouts.default')

@section('css')
	{{ HTML::style('css/table/style_1.css') }}
	{{ HTML::style('css/form/classroom.css') }}
	{{ HTML::style('css/js/course_time_selector.css') }}
	{{ HTML::style('css/column_item/style_1.css') }}
	{{ HTML::style('css/classroom_index.css') }}
@stop

@section('js')
	{{ HTML::script('js/course_time_selector.js') }}	
@stop

<?php View::share('titlePrefix', '教室管理'); ?>

@section('content')
	<h1>教室管理</h1>	
	
	{{ HtmlComposite::messageBlock() }}
	
	@if (isset($classroom))
		{{ HtmlComposite::back('classroom/') }}					
	@endif

	{{ FormList::open($classroom, URL::to(isset($classroom) ? 'classroom/edit/' . $classroom->classroom_id : 'classroom/add')) }}	
		{{ FormList::text('classroom_name', '教室名稱', array('required' => 'required', 'autofocus' => 'autofocus')) }}
		&nbsp;&nbsp;		
		{{ FormList::select('count', '同時使用班級數', array('range' => array(1, 10))) }}
		{{ FormList::hidden('course_time') }}
		{{ FormList::submit(isset($classroom) ? '更新' : '新增') }}
	{{ FormList::close() }}
	
	@if (isset($classroomList))
		<div id="classroom_list">	    		    		
			<table class="data_table table_style_1">
				<tr>
					<th>教室名稱（同時使用班級數）</th>
					<th>&nbsp;</th>
				</tr>
				@foreach ($classroomList as $classroomItem)
				<tr>    		
					<td class="classroom_name">
						{{ $classroomItem->classroom_name }}						
						{{ '（' . $classroomItem->count . '）' }}
						
					</td>
					<td class="classroom_command">
						{{ HtmlComposite::edit('classroom/edit/' . $classroomItem->classroom_id) }}
						{{ HtmlComposite::delete('classroom/delete/' . $classroomItem->classroom_id) }}
					</td>
				</tr>
				@endforeach
			</table>	    			    	
		</div>
	@endif
	
	<div id="teacher_course_time">			
		@include('course_time_selector', array('course_time' => isset($classroom) ? $classroom->course_time : null))
	</div>
	
	@if (isset($classroomCourseunit) && count($classroomCourseunit) != 0)	
		<div id="classroom_courseunit" class="column_item column_item_style_1">
			<ul>
				<li class="title">
					已設定排課
				</li>
				@foreach ($classroomCourseunit as $courseunit)
					<li>
						{{ $courseunit->classes->classes_name.  '[' . $courseunit->course->course_name . '](' . $courseunit->count . ')'}}
					</li>			
				@endforeach
			</ul>
		</div>	
	@endif 
@stop