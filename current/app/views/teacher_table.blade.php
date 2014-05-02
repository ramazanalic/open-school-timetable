@extends('layouts.default')

@section('css')
	{{ HTML::style('css/row_item/row_item_style_1.css') }}
	{{ HTML::style('css/column_item/column_item_style_2.css') }}
	{{ HTML::style('css/js/course_time_selector.css') }}
	{{ HTML::style('css/table_query.css') }}			
@stop

<?php View::share('titlePrefix', '教師課表查詢'); ?>

@section('content')
	<h1>教師課表查詢</h1>
	
	@if (isset($titleList))	
		<div class="row_item row_item_style_1" id="title_list">
			<ul>				
				<li {{ (isset($titleId) && $titleId == 'all') ? 'class="row_item_selected"' : '' }}>{{ HTML::link(URL::to('teacher_table/all'), '全部（' . count(Teacher::All()) . '）') }}</li>
				<li {{ (isset($titleId) && $titleId == '0') ? 'class="row_item_selected"' : '' }}>{{ HTML::link(URL::to('teacher_table/0'), '無職稱（' . Teacher::where('title_id', '=', 0)->count() . '）') }}</li>
		
				@if (isset($titleList))
					@foreach ($titleList as $title)
						<li {{ (isset($titleId) && $titleId == $title->title_id) ? 'class="row_item_selected"' : '' }}>{{ HTML::link(URL::to('teacher_table/' . $title->title_id), $title->title_name . '（'. $title->teacher()->count() . '）') }}</li>
					@endforeach
				@endif
			</ul>
		</div>
	@endif
	
	@if (isset($teacherList) && $teacherList->count() != 0)
		<div class="column_item column_item_style_2" id="teacher_column">
			<ul>
				@foreach ($teacherList as $teacher)
					<li class = "{{ (isset($teacherId) && $teacher->teacher_id == $teacherId) ? 'column_item_selected' : '' }}">
				    	{{ HTML::link(URL::to('teacher_table/' . $titleId. '/' . $teacher->teacher_id), $teacher->teacher_name) }}
				    </li>
				@endforeach
			</ul>
		</div>
	@endif
@stop
