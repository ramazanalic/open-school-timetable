@extends('layouts.default')

@section('css')
	{{ HTML::style('css/table/table_style_1.css') }}
	{{ HTML::style('css/row_item/row_item_style_1.css') }}
	{{ HTML::style('css/timetable_index.css') }}		
@stop

@section('content')
	<?php View::share('titlePrefix', '排課設定') ?>

	<h1>排課設定</h1>

	{{ HtmlComposite::messageBlock() }}

	<div class="row_item row_item_style_1" id="title_list">
		<ul>				
			<li {{ ($titleId == 'all') ? 'class="row_item_selected"' : '' }}>{{ HTML::link(URL::to('timetable'), '全部（' . count(Teacher::All()) . '）') }}</li>
			<li {{ ($titleId == '0') ? 'class="row_item_selected"' : '' }}>{{ HTML::link(URL::to('timetable/view_title/0'), '無職稱（' . Teacher::where('title_id', '=', 0)->count() . '）') }}</li>
	
			@if (isset($titleList))
				@foreach ($titleList as $title)
					<li {{ ($titleId == $title->title_id) ? 'class="row_item_selected"' : '' }}>{{ HTML::link(URL::to('timetable/view_title/' . $title->title_id), $title->title_name . '（'. $title->teacher()->count() . '）') }}</li>
				@endforeach
			@endif
		</ul>
	</div>
	
	@if ($teacherList->count() != 0)
		<table class="data_table table_style_1">
		    <tr>
		        <th class="teacher_name">姓名</th>		        		        		        
		        <th class="teacher_course_count">應上節數</th>
		        <th class="classes">導師班</th>		        		        
		    </tr>
		    @foreach ($teacherList as $teacher)
			    <tr>
			        <td class="teacher_name">{{ $teacher->teacher_name }}</td>			       			       
			        <td class="teacher_course_count">{{ $teacher->teacher_course_count }}</td>
			        <td class="classes">
			        	@if ($teacher->classes_id == 0)
			        		無
			        	@else
			        		<?php
			        			try {
									echo $teacher->classes()->first()->classes_name;
			        			}catch(Exception $e){
									echo '<div class="alert">查詢錯誤！</div>';
			        			}
			        		?>
			        	@endif
		            </td>			        			       		        
			    </tr>
		    @endforeach
		</table>
	@endif
@stop