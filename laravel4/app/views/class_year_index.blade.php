@extends('layouts.default')

@section('css')
	{{ HTML::style('css/table/table_style1.css') }}
	{{ HTML::style('css/class_year.css') }}
	{{ HTML::style('css/js/course_time_selector.css') }}
	{{ HTML::style('css/form/class_year_form.css') }}
@stop

@section('js')
	{{ HTML::script('js/year_course_selector.js') }}
@stop

@section('content')

	<h1>班級、年級管理</h1>

	{{ HtmlComposite::messageBlock() }}

	<div id="year_row">
		@if (isset($yearList))
	    	@foreach ($yearList as $yearItem)
	    		{{ HTML::link(URL::to('class_year/view_year/' . $yearItem->year_id), $yearItem->year_name, array('class' => (isset($year) && $yearItem->year_id == $year->year_id) ? 'year_item year_item_selected' : 'year_item')) }}
	    	@endforeach
	    @endif
	</div>

	<div id="year_form">
        {{ OstForm::open($year, URL::to((isset($year)) ? 'class_year/update_year/' . $year->year_id : 'class_year/add_year')) }}
        	<h2>{{ (isset($year)) ? '《' . $year->year_name . '》' : '新增年級' }}</h2>
        	{{ OstForm::text('year_name', '年級名稱', array('required' => 'required')) }}
        	{{ OstForm::hidden('course_time') }}
        </table>

	    <div id="year_course_time_selector">
	        <h2>上課時間</h2>
	        <table>
	            <tr id="day_row">
	                <th>&nbsp;</th>
	                <th>週一</th>
	                <th>週二</th>
	                <th>週三</th>
	                <th>週四</th>
	                <th>週五</th>
	            </tr>
	            <tr>
	                <td class="course_column">第一節</td>
	                <td class="course" id="course_1" data-selected="{{ (isset($year)) ? substr($year->course_time, 0, 1) : '0'}}">&nbsp;</td>
	                <td class="course" id="course_8" data-selected="{{ (isset($year)) ? substr($year->course_time, 7, 1) : '0'}}">&nbsp;</td>
	                <td class="course" id="course_15" data-selected="{{ (isset($year)) ? substr($year->course_time, 14, 1) : '0'}}">&nbsp;</td>
	                <td class="course" id="course_22" data-selected="{{ (isset($year)) ? substr($year->course_time, 21, 1) : '0'}}">&nbsp;</td>
	                <td class="course" id="course_29" data-selected="{{ (isset($year)) ? substr($year->course_time, 28, 1) : '0'}}">&nbsp;</td>
	            </tr>
	            <tr>
	                <td class="course_column">第二節</td>
	                <td class="course" id="course_2" data-selected="{{ (isset($year)) ? substr($year->course_time, 1, 1) : '0'}}">&nbsp;</td>
	                <td class="course" id="course_9" data-selected="{{ (isset($year)) ? substr($year->course_time, 8, 1) : '0'}}">&nbsp;</td>
	                <td class="course" id="course_16" data-selected="{{ (isset($year)) ? substr($year->course_time, 15, 1) : '0'}}">&nbsp;</td>
	                <td class="course" id="course_23" data-selected="{{ (isset($year)) ? substr($year->course_time, 22, 1) : '0'}}">&nbsp;</td>
	                <td class="course" id="course_30" data-selected="{{ (isset($year)) ? substr($year->course_time, 29, 1) : '0'}}">&nbsp;</td>
	            </tr>
	            <tr>
	                <td class="course_column">第三節</td>
	                <td class="course" id="course_3" data-selected="{{ (isset($year)) ? substr($year->course_time, 2, 1) : '0'}}">&nbsp;</td>
	                <td class="course" id="course_10" data-selected="{{ (isset($year)) ? substr($year->course_time, 9, 1) : '0'}}">&nbsp;</td>
	                <td class="course" id="course_17" data-selected="{{ (isset($year)) ? substr($year->course_time, 16, 1) : '0'}}">&nbsp;</td>
	                <td class="course" id="course_24" data-selected="{{ (isset($year)) ? substr($year->course_time, 23, 1) : '0'}}">&nbsp;</td>
	                <td class="course" id="course_31" data-selected="{{ (isset($year)) ? substr($year->course_time, 30, 1) : '0'}}">&nbsp;</td>
	            </tr>
	            <tr>
	                <td class="course_column">第四節</td>
	                <td class="course" id="course_4" data-selected="{{ (isset($year)) ? substr($year->course_time, 3, 1) : '0'}}">&nbsp;</td>
	                <td class="course" id="course_11" data-selected="{{ (isset($year)) ? substr($year->course_time, 10, 1) : '0'}}">&nbsp;</td>
	                <td class="course" id="course_18" data-selected="{{ (isset($year)) ? substr($year->course_time, 17, 1) : '0'}}">&nbsp;</td>
	                <td class="course" id="course_25" data-selected="{{ (isset($year)) ? substr($year->course_time, 24, 1) : '0'}}">&nbsp;</td>
	                <td class="course" id="course_32" data-selected="{{ (isset($year)) ? substr($year->course_time, 31, 1) : '0'}}">&nbsp;</td>
	            </tr>
	            <tr>
	                <td id="noon_break" colspan="6">午休</td>
	            </tr>
	            <tr>
	                <td class="course_column">第五節</td>
	                <td class="course" id="course_5" data-selected="{{ (isset($year)) ? substr($year->course_time, 4, 1) : '0'}}">&nbsp;</td>
	                <td class="course" id="course_12" data-selected="{{ (isset($year)) ? substr($year->course_time, 11, 1) : '0'}}">&nbsp;</td>
	                <td class="course" id="course_19" data-selected="{{ (isset($year)) ? substr($year->course_time, 18, 1) : '0'}}">&nbsp;</td>
	                <td class="course" id="course_26" data-selected="{{ (isset($year)) ? substr($year->course_time, 25, 1) : '0'}}">&nbsp;</td>
	                <td class="course" id="course_33" data-selected="{{ (isset($year)) ? substr($year->course_time, 32, 1) : '0'}}">&nbsp;</td>
	            </tr>
	            <tr>
	                <td class="course_column">第六節</td>
	                <td class="course" id="course_6" data-selected="{{ (isset($year)) ? substr($year->course_time, 5, 1) : '0'}}">&nbsp;</td>
	                <td class="course" id="course_13" data-selected="{{ (isset($year)) ? substr($year->course_time, 12, 1) : '0'}}">&nbsp;</td>
	                <td class="course" id="course_20" data-selected="{{ (isset($year)) ? substr($year->course_time, 19, 1) : '0'}}">&nbsp;</td>
	                <td class="course" id="course_27" data-selected="{{ (isset($year)) ? substr($year->course_time, 26, 1) : '0'}}">&nbsp;</td>
	                <td class="course" id="course_34" data-selected="{{ (isset($year)) ? substr($year->course_time, 33, 1) : '0'}}">&nbsp;</td>
	            </tr>
	            <tr>
	                <td class="course_column">第七節</td>
	                <td class="course" id="course_7" data-selected="{{ (isset($year)) ? substr($year->course_time, 6, 1) : '0'}}">&nbsp;</td>
	                <td class="course" id="course_14" data-selected="{{ (isset($year)) ? substr($year->course_time, 13, 1) : '0'}}">&nbsp;</td>
	                <td class="course" id="course_21" data-selected="{{ (isset($year)) ? substr($year->course_time, 20, 1) : '0'}}">&nbsp;</td>
	                <td class="course" id="course_28" data-selected="{{ (isset($year)) ? substr($year->course_time, 27, 1) : '0'}}">&nbsp;</td>
	                <td class="course" id="course_35" data-selected="{{ (isset($year)) ? substr($year->course_time, 34, 1) : '0'}}">&nbsp;</td>
	            </tr>
	        </table>
	    </div>

		<div id="year_form_command">
		    @if (isset($year))
		    	{{ HtmlComposite::delete('class_year/delete_year/' . $year->year_id, '刪除《' . $year->year_name . '》') }}
		    @endif

		    <?= Form::submit((isset($year)) ? '更新' : '新增'); ?>
	    </div>
	</div>

	@if (isset($year))
		<div id="class_area">

			{{ OstForm::open($classes, URL::to('class_year/add_classes/' . $year->year_id)) }}
		    <table class="dataList">
		    	<tr>
		    		<th class="classes_name">{{ Form::text('classes_name', '', array('required' => 'required', 'placeholder'=>'新增年級…')) }}</th>
		    		<th class="classes_command">{{ Form::submit('新增', array('id', 'add_classes')); }}</th>
	    		</tr>
	    	</table>
		    {{ OstForm::close() }}

			@if (isset($classes))
		    	@foreach ($classes as $classesItem)
					{{ OstForm::open(URL::to($classes, 'class_year/edit_classes/' . $classesItem->classes_id), 'POST', array('class' => 'hidden_form')) }}
					<table class="dataList">
		    			<tr>
		    				<td class="classes_name"><?= Form::text('classes_name', $classesItem->classes_name, array('required' => 'required', 'size' => '5')) ?></td>
		    				<td class="classes_command"><?= Form::submit('更新') ?><?= HtmlComposite::delete('class_year/delete_classes/' . $classesItem->classes_id) ?></td>
		    			</tr>
		    		</table>
		    		{{ Form::close() }}
		    	@endforeach
		    @endif
		</div>
	@endif
@stop