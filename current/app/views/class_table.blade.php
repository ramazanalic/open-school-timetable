@extends('layouts.default')

@section('css')
	{{ HTML::style('css/row_item/style_2.css') }}
	{{ HTML::style('css/column_item/style_1.css') }}
	{{ HTML::style('css/js/course_time_selector.css') }}
	{{ HTML::style('css/table/query.css') }}
	{{ HTML::style('css/class_table.css') }}			
@stop

<?php View::share('titlePrefix', '班級課表查詢'); ?>

@section('content')
	<h1>班級課表查詢</h1>	
	
	@if (isset($yearList))
		<div class="row_item row_item_style_2" id="year_row">
			<ul>		
		    	@foreach ($yearList as $yearItem)
		    		<li class = "{{ (isset($yearId) && $yearItem->year_id == $yearId) ? 'row_item_selected' : '' }}">
		    			{{ HTML::link(URL::to('class_table/' . $yearItem->year_id), $yearItem->year_name . '（' . $yearItem->classes()->count() . '）') }}
		    		</li>
		    	@endforeach
			</ul>
		</div>
	@endif
	
	@if (isset($classes))
		<div class="column_item column_item_style_1" id="class_column">
			<ul>		
		    	@foreach ($classes as $classItem)
		    		<li class = "{{ (isset($classesId) && $classItem->classes_id == $classesId) ? 'column_item_selected' : '' }}">		    										    		
		    			{{ HTML::link(URL::to('class_table/' . $yearId . '/' . $classItem->classes_id), (count($classItem->teacher) != 0) ? $classItem->classes_name . '（' . $classItem->teacher->teacher_name . '）' : $classItem->classes_name)}}		    			
		    		</li>
		    	@endforeach
			</ul>
		</div>
	@endif
		
	@if (isset($classTimeTable))
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
							@if ($classTimeTable[$i] != null)
								{{ $classTimeTable[$i]['course_name'] }}
								<br>
								{{ $classTimeTable[$i]['teacher_name'] }}
							@endif
						</td>
					@endfor
				</tr>
				<tr>
					<td class="course_column">第二節</td>
					@for ($i = 1; $i < 35; $i = $i + 7)
						<td class="course" id="course_{{$i}}">
							@if ($classTimeTable[$i] != null)
								{{ $classTimeTable[$i]['course_name'] }}
								<br>
								{{ $classTimeTable[$i]['teacher_name'] }}
							@endif
						</td>
					@endfor
				</tr>
				<tr>
					<td class="course_column">第三節</td>
					@for ($i = 2; $i < 35; $i = $i + 7)
						<td class="course" id="course_{{$i}}">
							@if ($classTimeTable[$i] != null)
								{{ $classTimeTable[$i]['course_name'] }}
								<br>
								{{ $classTimeTable[$i]['teacher_name'] }}
							@endif
						</td>
					@endfor
				</tr>
				<tr>
					<td class="course_column">第四節</td>
					@for ($i = 3; $i < 35; $i = $i + 7)
						<td class="course" id="course_{{$i}}">
							@if ($classTimeTable[$i] != null)
								{{ $classTimeTable[$i]['course_name'] }}
								<br>
								{{ $classTimeTable[$i]['teacher_name'] }}
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
							@if ($classTimeTable[$i] != null)
								{{ $classTimeTable[$i]['course_name'] }}
								<br>
								{{ $classTimeTable[$i]['teacher_name'] }}
							@endif
						</td>
					@endfor
				</tr>
				<tr>
					<td class="course_column">第六節</td>
					@for ($i = 5; $i < 35; $i = $i + 7)
						<td class="course" id="course_{{$i}}">
							@if ($classTimeTable[$i] != null)
								{{ $classTimeTable[$i]['course_name'] }}
								<br>
								{{ $classTimeTable[$i]['teacher_name'] }}
							@endif
						</td>
					@endfor
				</tr>
				<tr>
					<td class="course_column">第七節</td>
					@for ($i = 6; $i < 35; $i = $i + 7)
						<td class="course" id="course_{{$i}}">
							@if ($classTimeTable[$i] != null)
								{{ $classTimeTable[$i]['course_name'] }}
								<br>
								{{ $classTimeTable[$i]['teacher_name'] }}
							@endif
						</td>
					@endfor
				</tr>
			</table>
		</div>	
	@endif	
@stop
