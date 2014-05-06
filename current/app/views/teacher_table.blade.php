@extends('layouts.default')

@section('css')
	{{ HTML::style('css/row_item/style_1.css') }}
	{{ HTML::style('css/column_item/style_2.css') }}
	{{ HTML::style('css/js/course_time_selector.css') }}
	{{ HTML::style('css/table/query.css') }}
	{{ HTML::style('css/teacher_table.css') }}				
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
	
	@if (isset($teacherTimeTable))
		<div id="course_time_selector">	
			<table>
				<tr id="day_row">
					<th id="course_time_description">上課時間</th>
					<th>週一</th>
					<th>週二</th>
					<th>週三</th>
					<th>週四</th>
					<th>週五</th>
				</tr>
				<tr>
					<td class="course_column">第一節</td>
					@for ($i = 0; $i < 35; $i = $i + 7)
						<td class="course" id="course_{{$i}}">
							@if ($teacherTimeTable[$i] != null)
								{{ $teacherTimeTable[$i]['course_name'] }}
								<br>
								{{ $teacherTimeTable[$i]['classes_name'] }}
								
								{{ (isset($teacherTimeTable[$i]['classroom_name'])) ? '<br>' . $teacherTimeTable[$i]['classroom_name'] : '' }}
							@endif
						</td>
					@endfor
				</tr>
				<tr>
					<td class="course_column">第二節</td>
					@for ($i = 1; $i < 35; $i = $i + 7)
						<td class="course" id="course_{{$i}}">
							@if ($teacherTimeTable[$i] != null)
								{{ $teacherTimeTable[$i]['course_name'] }}
								<br>
								{{ $teacherTimeTable[$i]['classes_name'] }}
								
								{{ (isset($teacherTimeTable[$i]['classroom_name'])) ? '<br>' . $teacherTimeTable[$i]['classroom_name'] : '' }}
							@endif
						</td>
					@endfor
				</tr>
				<tr>
					<td class="course_column">第三節</td>
					@for ($i = 2; $i < 35; $i = $i + 7)
						<td class="course" id="course_{{$i}}">
							@if ($teacherTimeTable[$i] != null)
								{{ $teacherTimeTable[$i]['course_name'] }}
								<br>
								{{ $teacherTimeTable[$i]['classes_name'] }}
								
								{{ (isset($teacherTimeTable[$i]['classroom_name'])) ? '<br>' . $teacherTimeTable[$i]['classroom_name'] : '' }}
							@endif
						</td>
					@endfor
				</tr>
				<tr>
					<td class="course_column">第四節</td>
					@for ($i = 3; $i < 35; $i = $i + 7)
						<td class="course" id="course_{{$i}}">
							@if ($teacherTimeTable[$i] != null)
								{{ $teacherTimeTable[$i]['course_name'] }}
								<br>
								{{ $teacherTimeTable[$i]['classes_name'] }}
								
								{{ (isset($teacherTimeTable[$i]['classroom_name'])) ? '<br>' . $teacherTimeTable[$i]['classroom_name'] : '' }}
							@endif
						</td>
					@endfor
				</tr>
				<tr>
					<td id="noon_break" colspan="6">午休</td>
				</tr>
				<tr>
					<td class="course_column">第五節</td>
					@for ($i = 4; $i < 35; $i = $i + 7)
						<td class="course" id="course_{{$i}}">
							@if ($teacherTimeTable[$i] != null)
								{{ $teacherTimeTable[$i]['course_name'] }}
								<br>
								{{ $teacherTimeTable[$i]['classes_name'] }}
								
								{{ (isset($teacherTimeTable[$i]['classroom_name'])) ? '<br>' . $teacherTimeTable[$i]['classroom_name'] : '' }}
							@endif
						</td>
					@endfor
				</tr>
				<tr>
					<td class="course_column">第六節</td>
					@for ($i = 5; $i < 35; $i = $i + 7)
						<td class="course" id="course_{{$i}}">
							@if ($teacherTimeTable[$i] != null)
								{{ $teacherTimeTable[$i]['course_name'] }}
								<br>
								{{ $teacherTimeTable[$i]['classes_name'] }}
								
								{{ (isset($teacherTimeTable[$i]['classroom_name'])) ? '<br>' . $teacherTimeTable[$i]['classroom_name'] : '' }}
							@endif
						</td>
					@endfor
				</tr>
				<tr>
					<td class="course_column">第七節</td>
					@for ($i = 6; $i < 35; $i = $i + 7)
						<td class="course" id="course_{{$i}}">
							@if ($teacherTimeTable[$i] != null)
								{{ $teacherTimeTable[$i]['course_name'] }}
								<br>
								{{ $teacherTimeTable[$i]['classes_name'] }}
								
								{{ (isset($teacherTimeTable[$i]['classroom_name'])) ? '<br>' . $teacherTimeTable[$i]['classroom_name'] : '' }}
							@endif
						</td>
					@endfor
				</tr>
			</table>
		</div>	
	@endif	
@stop
