@extends('layouts.default')

@section('css')
	{{ HTML::style('css/table/style_1.css') }}
	{{ HTML::style('css/table/style_2.css') }}
	{{ HTML::style('css/row_item/style_1.css') }}
	{{ HTML::style('css/form/course_unit.css') }}
	{{ HTML::style('css/js/course_time_selector.css') }}
	{{ HTML::style('css/timetable.css') }}		
@stop

@section('js')
	{{ HTML::script('js/course_time_selector.js') }}
	{{ HTML::script('js/show_course_limit.js') }}
	{{ HTML::script('js/sorttable.js') }}	
@stop

<?php View::share('titlePrefix', '排課設定') ?>
<?php View::share('selectUrl', 'timetable'); ?>

@section('content')	
	<h1>排課設定</h1>
	
	{{ Helper::message(Session::get('conflictError'), true) }}

	<div class="row_item row_item_style_1" id="title_list">
		<ul>				
			<li {{ ($titleId == 'all') ? 'class="row_item_selected"' : '' }}>{{ link_to('timetable', '全部（' . count(Teacher::All()) . '）') }}</li>
			<li {{ ($titleId == '0') ? 'class="row_item_selected"' : '' }}>{{ link_to('timetable/view_title/0', '無職稱（' . Teacher::where('title_id', '=', 0)->count() . '）') }}</li>
	
			@if (isset($titleList))
				@foreach ($titleList as $title)
					<li {{ ($titleId == $title->title_id) ? 'class="row_item_selected"' : '' }}>{{ link_to('timetable/view_title/' . $title->title_id, $title->title_name . '（'. $title->teacher()->count() . '）') }}</li>
				@endforeach
			@endif
		</ul>
	</div>
	
	@if ($teacherList->count() != 0)
		<table id="teacher_list" class="data_table table_style_1 sortable">
		    <tr>
		        <th class="teacher_name">姓名</th>		        		        		        
		        <th class="teacher_course_count">應上節數</th>
		        <th class="classes">導師班</th>	
		        <th class="command sorttable_nosort">&nbsp;</th>	        		        
		    </tr>
		    @foreach ($teacherList as $teacherItem)
			    <tr>
			        <td class="teacher_name">{{ $teacherItem->teacher_name }}</td>			       			       
			        <td class="teacher_course_count">
			        	{{ $teacherItem->teacher_course_count }}
			        	<?php
							$teacher_has_course_count = 0;
							
							//導師：原班的課 - 科任 + 自己上別班的課
							if ($teacherItem->classes_id != 0) {
								$teacher_has_course_count += substr_count($teacherItem->classes->year->course_time, '1');
								$teacher_has_course_count -= Courseunit::where('teacher_id', '<>', $teacherItem->teacher_id)->where('classes_id', '=', $teacherItem->classes_id)->sum('count');
								$teacher_has_course_count += Courseunit::where('teacher_id', '=', $teacherItem->teacher_id)->where('classes_id', '<>', $teacherItem->classes_id)->sum('count');
							} else {
								// 科任：安排的課
								$teacher_has_course_count += $teacherItem->courseunit()->sum('count');
							}
	
							if ($teacher_has_course_count > $teacherItem->teacher_course_count) {
								$courseTimeDiffClass = 'plus';
							} elseif ($teacher_has_course_count < $teacherItem->teacher_course_count) {
								$courseTimeDiffClass = 'minus';
							} else {
								$courseTimeDiffClass = 'zero';
							}
	
							echo '（<span class="' . $courseTimeDiffClass . '">' . $teacher_has_course_count . '</span>）';
						?>
			        </td>
			        <td class="classes">
			        	@if ($teacherItem->classes_id == 0)
			        		無
			        	@else
			        		<?php
			        			try {
									echo $teacherItem->classes->classes_name;
			        			}catch(Exception $e){
									echo '<div class="alert">查詢錯誤！</div>';
			        			}
			        		?>
			        	@endif
		            </td>
		            <td class="command{{ (isset($teacherId) && $teacherItem->teacher_id == $teacherId) ? ' edit_selected' : '' }}">
		            	{{ Helper::edit('timetable/view_title/' . $titleId . '/' . $teacherItem->teacher_id, '設定排課') }}		            	
		            </td>			        			       		        
			    </tr>
		    @endforeach
		</table>
	@endif

	@if (isset($courseUnits))		
		<div id="course_unit_form">	
			@if (isset($courseUnit))
				{{ FormList::open($courseUnit, 'timetable/edit/' .$titleId . '/' . $teacherId . '/' . $courseUnit->course_unit_id, array('class' => 'edit_selected')) }}
			@else
				{{ FormList::open('', 'timetable/add/' . $titleId . '/' . $teacher->teacher_id) }}
			@endif
	
			{{ FormList::description('設定〔' . $teacher->teacher_name . '〕排課資料') }}
			<br>
			{{ FormList::select('classes_id', '班級', array('valueArray' => Classes::getClassesSelectArray(false), 'required' => 'required')) }}
			{{ FormList::select('course_id', '課程', array('required' => 'required'), array('Course', 'course_id', 'course_name')) }}	
			{{ FormList::select('count', '節數', array('range' => array(1, 15))) }}
			{{ FormList::select('classroom_id', '使用教室', array('valueArray' => array('0' => '無'), 'required' => 'required'), array('Classroom', 'classroom_id', 'classroom_name')) }}
	
			@if (isset($courseUnit))
				<?php $courseUnitLimit = unserialize($courseUnit->course_unit_limit) ?>				
				{{ FormList::select('combination', '組合節數', array('range' => array(1, 3), 'value' => $courseUnitLimit['combination'])) }}
				{{ FormList::select('repeat', '同天同班可重複排課', array('valueArray' => array('0' => '否', '1' => '是'), 'value' => $courseUnitLimit['repeat'])) }}
				{{ FormList::checkbox('limit_course_time', '限制排課時間', 1, $courseUnitLimit['limit_course_time']) }}
				{{ FormList::hidden('course_time', $courseUnitLimit['course_time']) }}
			@else
				{{ FormList::select('combination', '組合節數', array('range' => array(1, 3))) }}
				{{ FormList::select('repeat', '同天同班可重複排課', array('valueArray' => array('0' => '否', '1' => '是'))) }}
				{{ FormList::checkbox('limit_course_time', '限制排課時間', 1) }}
				{{ FormList::hidden('course_time') }}
			@endif
	
			{{ FormList::hidden('teacher_id', $teacher->teacher_id) }}
			&nbsp;&nbsp;
			{{ FormList::submit((isset($courseUnit)) ? '更新' : '新增') }}
			{{ FormList::close() }}				

			@if ($courseUnits->count() != 0)				
				<table class="data_table table_style_2" id="course_unit_list">
				    <tr>
				        <th class="classes_name">班級</th>
				        <th class="course_name">課程</th>	        		        
				        <th class="count">節數</th>
				        <th class="classroom_name">使用教室</th>
				        <th class="combination">組合節數</th>
				        <th class="repeat">同天同班<br>可重複排課</th>
				        <th class="limit_course_time">限制排課時間</th>
				        <th class="command">&nbsp;</th>
				    </tr>
				    @foreach ($courseUnits as $courseUnitItem)
					    @if (isset($courseUnit) && $courseUnitItem->course_unit_id == $courseUnit->course_unit_id)
					    	<tr class="data_row edit_selected">
					    @else
					    	<tr class="data_row">
					    @endif
					    	<td class="classes_name">{{ $courseUnitItem->classes->classes_name }}</td>
					        <td class="course_name">{{ $courseUnitItem->course->course_name }}</td>	        		        
					        <td class="count">{{ $courseUnitItem->count }}</td>
					        <td class="classroom_name">		        	
					        	@if ($courseUnitItem->classroom_id == 0)
					        		無
					        	@else
					        		<?php
					        			try {
											echo $courseUnitItem->classroom->classroom_name;
					        			}catch(Exception $e){
											echo '<div class="alert">查詢錯誤！</div>';
					        			}
					        		?>
					        	@endif 		        			        	
					        </td>
					        
					        <?php $courseLimitTemp = unserialize($courseUnitItem->course_unit_limit) ?>
					        
					        <td class="combination">{{ $courseLimitTemp['combination'] }}</td>
					        <td class="repeat">{{ ($courseLimitTemp['repeat']) ? '是' : '否' }}</td>
					        <td class="limit_course_time">{{ ($courseLimitTemp['limit_course_time']) ? '是' : '否' }}</td>		
					        <td class="command">
					        	{{ Helper::edit('timetable/view_title/' . $titleId . '/' . $teacherId . '/' . $courseUnitItem->course_unit_id) }}					        		        	
					        	{{ Helper::delete('timetable/delete/' . $titleId . '/' . $courseUnitItem->course_unit_id) }}
					        </td>		      		       
					    </tr>		   
				    @endforeach
				</table>				
			@endif
			
			@include('course_time_selector', array('course_time' => isset($courseUnitLimit) ? $courseUnitLimit['course_time'] : ''))	
		</div>				
	@endif
@stop