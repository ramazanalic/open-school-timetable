@if (isset($course_unit))
	{{ FormList::open($course_unit, 'timetable/edit/' . $teacher->teacher_id . '/' . $course_unit->course_unit_id) }}
@else
	{{ FormList::open('', 'timetable/add/' . $titleId . '/' . $teacher->teacher_id) }}
@endif

{{ FormList::description('設定〔' . $teacher->teacher_name . '〕排課資料') }}
<br>
{{ FormList::select('classes_id', '班級', array('required' => 'required'), array('Classes', 'classes_id', 'classes_name')) }}
{{ FormList::select('course_id', '課程', array('required' => 'required'), array('Course', 'course_id', 'course_name')) }}	
{{ FormList::select('count', '節數', array('range' => array(1, 15))) }}
{{ FormList::select('classroom_id', '使用教室', array('valueArray' => array('0' => '無'), 'required' => 'required'), array('Classroom', 'classroom_id', 'classroom_name')) }}

@if (isset($course_unit))
	<?php $course_limit = unserialize($course_unit->course_unit_limit) ?>
	{{ FormList::select('combination', '組合節數', array('range' => array(1, 5), 'value' => $course_limit['combination'])) }}
	{{ FormList::select('repeat', '同天同班可重複排課', array('valueArray' => array('0' => '否', '1' => '是'), 'value' => $course_limit['repeat'])) }}
	{{ FormList::checkbox('limit_course_time', '限制排課時間', 1, $course_limit['limit_course_time']) }}
	{{ FormList::hidden('course_time', $course_limit['course_time']) }}
@else
	{{ FormList::select('combination', '組合節數', array('range' => array(1, 5))) }}
	{{ FormList::select('repeat', '同天同班可重複排課', array('valueArray' => array('0' => '否', '1' => '是'))) }}
	{{ FormList::checkbox('limit_course_time', '限制排課時間', 1) }}
	{{ FormList::hidden('course_time') }}
@endif

{{ FormList::hidden('teacher_id', $teacher->teacher_id) }}
&nbsp;&nbsp;
{{ FormList::submit((isset($course_unit)) ? '更新' : '新增') }}
{{ FormList::close() }}

@include('course_time_selector', array('course_time' => (isset($course_limit) ? $course_limit['course_time'] : '')))

{{ HTML::script('js/course_time_selector.js') }}

@if ($course_units->count() != 0)
	{{ Form::open(array('url' => 'timetable/edit/' . $teacher->teacher_id . '/view_title/' . $titleId)) }}
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
	    @foreach ($course_units as $course_unit_item)
		    @if (isset($course_unit) && $course_unit_item->course_unit_id == $course_unit->course_unit_id)
		    	<tr class="data_row edit_select">
		    @else
		    	<tr class="data_row">
		    @endif
		    	<td class="classes_name">{{ $course_unit_item->classes->classes_name }}</td>
		        <td class="course_name">{{ $course_unit_item->course->course_name }}</td>	        		        
		        <td class="count">{{ $course_unit_item->count }}</td>
		        <td class="classroom_name">		        	
		        	@if ($course_unit_item->classroom_id == 0)
		        		無
		        	@else
		        		<?php
		        			try {
								echo $course_unit_item->classroom->classroom_name;
		        			}catch(Exception $e){
								echo '<div class="alert">查詢錯誤！</div>';
		        			}
		        		?>
		        	@endif 		        			        	
		        </td>
		        
		        <?php $course_limit = unserialize($course_unit_item->course_unit_limit) ?>
		        
		        <td class="combination">{{ $course_limit['combination'] }}</td>
		        <td class="repeat">{{ ($course_limit['repeat']) ? '是' : '否' }}</td>
		        <td class="limit_course_time">{{ ($course_limit['limit_course_time']) ? '是' : '否' }}</td>		
		        <td class="command">
		        	{{ HtmlComposite::edit('timetable/edit/' . $teacher->teacher_id . '/' . $course_unit_item->course_unit_id) }}
		        	{{ HtmlComposite::delete('timetable/delete/' . $course_unit_item->course_unit_id) }}
		        </td>		      		       
		    </tr>		   
	    @endforeach
	</table>
	{{ Form::close() }}
@endif
{{ HTML::script('js/show_course_unit_edit_form.js') }}
