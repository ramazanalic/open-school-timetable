@extends('layouts.default')

@section('css')
	{{ HTML::style('css/table/table_style_1.css') }}
	{{ HTML::style('css/table/table_style_2.css') }}
	{{ HTML::style('css/row_item/row_item_style_1.css') }}
	{{ HTML::style('css/form/course_unit_form.css') }}
	{{ HTML::style('css/js/course_time_selector.css') }}
	{{ HTML::style('css/timetable_index.css') }}		
@stop

@section('js')
	{{ HTML::script('js/show_course_unit_form.js') }}	
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
		<table id="teacher_list" class="data_table table_style_1">
		    <tr>
		        <th class="teacher_name">姓名</th>		        		        		        
		        <th class="teacher_course_count">應上節數</th>
		        <th class="classes">導師班</th>	
		        <th class="command">&nbsp;</th>	        		        
		    </tr>
		    @foreach ($teacherList as $teacher)
			    <tr>
			        <td class="teacher_name">{{ $teacher->teacher_name }}</td>			       			       
			        <td class="teacher_course_count">
			        	{{ $teacher->teacher_course_count }}
			        	<?php
			        		// 計算教師已經設定的節數
			        		$teacher_has_course_count = 0;							
							foreach ($teacher->courseunit()->get() as $courseunit) {								
								$teacher_has_course_count += $courseunit->count;								
							}
			        		
			        		// 如果為導師，加入導師節數									        		
			        		if ($teacher->classes_id != 0) {
			        			$class_has_course_count = 0;
			        			foreach ($teacher->classes->courseunit()->get() as $courseunit) {																																														
									$class_has_course_count += $courseunit->count;									 
								}			        			
			        			$teacher_has_course_count += substr_count($teacher->classes->year->course_time, '1') - $class_has_course_count;											        		
							} 						
							
							if ($teacher_has_course_count > $teacher->teacher_course_count) {
								$courseTimeDiffClass = 'plus';								
							} elseif ($teacher_has_course_count < $teacher->teacher_course_count) {
								$courseTimeDiffClass = 'minus';
							} else {
								$courseTimeDiffClass = 'zero';
							}
							
							echo '(<span class="' . $courseTimeDiffClass . '">' . $teacher_has_course_count . '</span>)'; 
			        	?>
			        </td>
			        <td class="classes">
			        	@if ($teacher->classes_id == 0)
			        		無
			        	@else
			        		<?php
			        			try {
									echo $teacher->classes->classes_name;
			        			}catch(Exception $e){
									echo '<div class="alert">查詢錯誤！</div>';
			        			}
			        		?>
			        	@endif
		            </td>
		            <td class="command">
		            	{{ Html::link('#' . $teacher->teacher_id, '設定排課(' . $teacher->courseunit()->count() . ')', array('class' => 'showCourseUnitForm edit_link', 'data-teacher_id' => $teacher->teacher_id, 'data-selected' => 0)) }}
		            </td>			        			       		        
			    </tr>
		    @endforeach
		</table>
	@endif
	
	<div id="course_unit_form">		
	</div>
@stop