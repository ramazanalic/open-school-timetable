@extends('layouts.default')

@section('css')
	{{ HTML::style('css/table/style_1.css') }}
	{{ HTML::style('css/row_item/style_2.css') }}
	{{ HTML::style('css/column_item/style_1.css') }}	
	{{ HTML::style('css/js/course_time_selector.css') }}
	{{ HTML::style('css/form/class_year.css') }}
	{{ HTML::style('css/class_year.css') }}
@stop

@section('js')
	{{ HTML::script('js/course_time_selector.js') }}
	{{ HTML::script('js/show_classes_courseunit.js') }}
@stop

<?php View::share('titlePrefix', '班級、年級管理' . (isset($year->year_name) ? ' - ' .$year->year_name : '')); ?>
<?php View::share('selectUrl', 'class_year'); ?>

@section('content')
	<h1>班級、年級管理</h1>

	{{ Helper::message() }}

	@if (isset($yearList))
		<div class="row_item row_item_style_2" id="year_row">
			@if (isset($year))				
				{{ Helper::back('class_year/') }}							
			@endif
			
			<ul>				
		    	@foreach ($yearList as $yearItem)
		    		<li class="{{ (isset($year) && $yearItem->year_id == $year->year_id) ? 'row_item_selected' : '' }}">
		    			{{ link_to('class_year/view_year/' . $yearItem->year_id, $yearItem->year_name . '（' . $yearItem->classes()->count() . '）') }}
		    		</li>
		    	@endforeach
			</ul>
		</div>
	@endif
	
	@if (isset($year))
		<div id="class_area">
			{{ Form::open(array('url' => 'class_year/add_classes/' . $year->year_id)) }}
				<table class="data_table table_style_1">
			    	<tr>
			    		<th class="classes_name">{{ Form::text('classes_name', '', array('required' => 'required', 'placeholder' => '新增班級…', 'autofocus' => 'autofocus')) }}</th>
			    		<th class="teacher">
			    			{{ FormList::select('teacher_id', '導師', array('valueArray' => array('0' => '無'), 'required' => 'required'), array('Teacher', 'teacher_id', 'teacher_name'), 0) }}	
			    		</th>			    		
			    		<th class="classes_command">{{ Form::submit('新增班級', array('id' => 'add_classes')) }}</th>			    		
		    		</tr>
		    	</table>
	    	{{ Form::close() }}

			@if (isset($classes))
		    	@foreach ($classes as $classesItem)
		    		{{ Form::open(array('url' => 'class_year/update_classes/' . $classesItem->classes_id . '/' . $year->year_id)) }}
	    				<table class="data_table table_style_1">
		    				<tr>
		    					<td class="classes_name">{{ Form::text('classes_name', $classesItem->classes_name, array('required' => 'required', 'size' => '5')) }}</td>
		    					<td class="teacher">			    						
			    					{{ FormList::select('teacher_id', '導師', array('valueArray' => Teacher::getTeacherSelectArray(), 'value' => $classesItem->teacher_id, 'required' => 'required'), null, 0) }}
			    				</th>
		    					<td class="classes_command">
		    						{{ Form::submit('更新') }}		    						
		    						{{ Helper::delete('class_year/delete_classes/' . $classesItem->classes_id . '/' . $year->year_id) }}		    						
		    						{{ link_to('#' . $classesItem->classes_id, '顯示班級排課(' . $classesItem->courseunit()->count() . ')', array('class' => 'showClassesCourseunit edit_link', 'data-classes_id' => $classesItem->classes_id)) }}
		    					</td>
		    				</tr>
		    			</table>
		    		{{ Form::close() }}
		    	@endforeach
		    @endif
		</div>
	@endif	

	<div id="year_form">
        {{ FormList::open($year, isset($year) ? 'class_year/update_year/' . $year->year_id : 'class_year/add_year') }}
        	{{ FormList::text('year_name', (isset($year)) ? '更改年級名稱' : '新增年級', array('required' => 'required')) }}
        	{{ FormList::hidden('course_time') }}
        	</ul>
			
			@include('course_time_selector', array('course_time' => (isset($year)) ? $year->course_time : null))
		   
			<div id="year_form_command">
			    @if (isset($year))
			    	{{ Helper::delete('class_year/delete_year/' . $year->year_id, '刪除《' . $year->year_name . '》') }}
			    @endif
	
			    <?= Form::submit((isset($year)) ? '更新' : '新增'); ?>
		    </div>
	   	</form>
	</div>

	@if (isset($classes))	
    	@foreach ($classes as $classesItem)     		
    		<?php $classesCourseunit = $classesItem->courseunit; ?>     			
    		@if (isset($classesCourseunit) && count($classesCourseunit) != 0)    			    		 		
				<div id="classes_courseunit_{{ $classesItem->classes_id }}" class="column_item column_item_style_1 classes_courseunit">
					<ul>
						<li class="title">
							{{ $classesItem->classes_name }}已設定排課
						</li>
						@foreach ($classesCourseunit as $courseunit)					
							<li>
								{{ Helper::edit('timetable/view_title/' . $courseunit->teacher->title->title_id . '/' . $courseunit->teacher->teacher_id . '/' . $courseunit->course_unit_id, $courseunit->teacher->teacher_name. '[' . $courseunit->course->course_name . '](' . $courseunit->count . '節)') }}								
							</li>
						@endforeach
					</ul>
				</div>						
			@endif				
		@endforeach			
	@endif	
@stop