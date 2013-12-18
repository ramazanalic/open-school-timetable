@extends('layouts.default')

@section('css')
	{{ HTML::style('css/table/table_style_1.css') }}
	{{ HTML::style('css/row_item/row_item_style_2.css') }}	
	{{ HTML::style('css/js/course_time_selector.css') }}
	{{ HTML::style('css/form/class_year_form.css') }}
	{{ HTML::style('css/class_year.css') }}
@stop

@section('js')
	{{ HTML::script('js/year_course_selector.js') }}
@stop

@section('content')
	<?php View::share('titlePrefix', '班級、年級管理' . (isset($year->year_name) ? ' - ' .$year->year_name : '')); ?>
	
	<h1>班級、年級管理</h1>

	{{ HtmlComposite::messageBlock() }}

	@if (isset($yearList))
		<div class="row_item row_item_style_1" id="year_row">
			<ul>		
		    	@foreach ($yearList as $yearItem)
		    		<li class = "{{ (isset($year) && $yearItem->year_id == $year->year_id) ? 'row_item_selected' : '' }}">
		    			{{ HTML::link(URL::to('class_year/view_year/' . $yearItem->year_id), $yearItem->year_name . '（' . $yearItem->classes()->count() . '）') }}
		    		</li>
		    	@endforeach
			</ul>
		</div>
	@endif	

	<div id="year_form">
        {{ FormList::open($year, URL::to((isset($year)) ? 'class_year/update_year/' . $year->year_id : 'class_year/add_year')) }}
        	{{ FormList::text('year_name', (isset($year)) ? '更改年級名稱' : '新增年級', array('required' => 'required')) }}
        	{{ FormList::hidden('course_time') }}
        	</ul>

		    <div id="year_course_time_selector">
		        <table>
		            <tr id="day_row">
		                <th id="year_course_time_description">上課時間</th>
		                <th>週一</th>
		                <th>週二</th>
		                <th>週三</th>
		                <th>週四</th>
		                <th>週五</th>
		            </tr>
		            <tr>
		                <td class="course_column">第一節</td>
		                @for ($i = 1; $i <= 35; $i = $i + 7)
		                	<td class="course" id="course_{{$i}}" data-selected="{{ (isset($year)) ? substr($year->course_time, $i-1, 1) : '0'}}">&nbsp;</td>
		                @endfor		                
		            </tr>
		            <tr>
		                <td class="course_column">第二節</td>
		                @for ($i = 2; $i <= 35; $i = $i + 7)
		                	<td class="course" id="course_{{$i}}" data-selected="{{ (isset($year)) ? substr($year->course_time, $i-1, 1) : '0'}}">&nbsp;</td>
		                @endfor			                
		            </tr>
		            <tr>
		                <td class="course_column">第三節</td>
		                @for ($i = 3; $i <= 35; $i = $i + 7)
		                	<td class="course" id="course_{{$i}}" data-selected="{{ (isset($year)) ? substr($year->course_time, $i-1, 1) : '0'}}">&nbsp;</td>
		                @endfor		                
		            </tr>
		            <tr>
		                <td class="course_column">第四節</td>
		                @for ($i = 4; $i <= 35; $i = $i + 7)
		                	<td class="course" id="course_{{$i}}" data-selected="{{ (isset($year)) ? substr($year->course_time, $i-1, 1) : '0'}}">&nbsp;</td>
		                @endfor		              		                
		            </tr>
		            <tr>
		                <td id="noon_break" colspan="6">午休</td>
		            </tr>
		            <tr>
		                <td class="course_column">第五節</td>
		                @for ($i = 5; $i <= 35; $i = $i + 7)
		                	<td class="course" id="course_{{$i}}" data-selected="{{ (isset($year)) ? substr($year->course_time, $i-1, 1) : '0'}}">&nbsp;</td>
		                @endfor		                		                
		            </tr>
		            <tr>
		                <td class="course_column">第六節</td>
		                @for ($i = 6; $i <= 35; $i = $i + 7)
		                	<td class="course" id="course_{{$i}}" data-selected="{{ (isset($year)) ? substr($year->course_time, $i-1, 1) : '0'}}">&nbsp;</td>
		                @endfor		                
		            </tr>
		            <tr>
		                <td class="course_column">第七節</td>
		                @for ($i = 7; $i <= 35; $i = $i + 7)
		                	<td class="course" id="course_{{$i}}" data-selected="{{ (isset($year)) ? substr($year->course_time, $i-1, 1) : '0'}}">&nbsp;</td>
		                @endfor		                
		            </tr>
		        </table>
		    </div>

			<div id="year_form_command">
			    @if (isset($year))
			    	{{ HtmlComposite::delete('class_year/delete_year/' . $year->year_id, '刪除《' . $year->year_name . '》') }}
			    @endif
	
			    <?= Form::submit((isset($year)) ? '更新' : '新增'); ?>
		    </div>
	   	</form>
	</div>

	@if (isset($year))
		<div id="class_area">
			{{ Form::open(array('url' => URL::to('class_year/add_classes/' . $year->year_id))) }}
				<table class="data_table table_style_1">
			    	<tr>
			    		<th class="classes_name">{{ Form::text('classes_name', '', array('required' => 'required', 'placeholder' => '新增班級…', 'autofocus' => 'autofocus')) }}</th>
			    		<th class="teacher">
			    			{{ FormList::select('teacher_id', '導師', array('valueArray' => array('0' => '無'), 'required' => 'required'), array('Teacher', 'teacher_id', 'teacher_name'), 0) }}	
			    		</th>			    		
			    		<th class="classes_command">{{ Form::submit('新增', array('id' => 'add_classes')) }}</th>			    		
		    		</tr>
		    	</table>
	    	{{ Form::close() }}

			@if (isset($classes))
		    	@foreach ($classes as $classesItem)
		    		{{ Form::open(array('url' => URL::to('class_year/update_classes/' . $classesItem->classes_id . '/' . $year->year_id))) }}
	    				<table class="data_table table_style_1">
		    				<tr>
		    					<td class="classes_name">{{ Form::text('classes_name', $classesItem->classes_name, array('required' => 'required', 'size' => '5')) }}</td>
		    					<td class="teacher">
			    					{{ FormList::select('teacher_id', '導師', array('valueArray' => array('0' => '無'), 'value' => $classesItem->teacher_id, 'required' => 'required'), array('Teacher', 'teacher_id', 'teacher_name'), 0) }}	
			    				</th>
		    					<td class="classes_command">{{ Form::submit('更新') . '&nbsp;&nbsp;' . HtmlComposite::delete('class_year/delete_classes/' . $classesItem->classes_id . '/' . $year->year_id) }}</td>
		    				</tr>
		    			</table>
		    		{{ Form::close() }}
		    	@endforeach
		    @endif
		</div>
	@endif
@stop