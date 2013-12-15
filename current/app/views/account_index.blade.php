@extends('layouts.default')

@section('css')
	{{ HTML::style('css/table/table_style_1.css') }}
	{{ HTML::style('css/form/class_year_form.css') }}
	{{ HTML::style('css/account_index.css') }}
@stop

@section('content')
	<h1>帳號管理</h1>

	{{ HtmlComposite::messageBlock() }}

	<div id="title_list">
		@if ($titleId >= 1)
			{{ Form::open(array('url' => URL::to('account/update_title/' . $titleId))) }}
		@else
			{{ Form::open(array('url' => URL::to('account/add_title/'))) }}
		@endif

		<ul>
			<li {{ ($titleId == 'all') ? 'class="title_selected"' : '' }}>{{ HTML::link(URL::to('account'), '全部（' . count(Teacher::All()) . '）') }}</li>
			<li {{ ($titleId == '0') ? 'class="title_selected"' : '' }}>{{ HTML::link(URL::to('account/view_title/0'), '無職稱（' . Teacher::where('title_id', '=', 0)->count() . '）') }}</li>

			@if (isset($titleList))
				@foreach ($titleList as $title)
					<li {{ ($titleId == $title->title_id) ? 'class="title_selected"' : '' }}>{{ HTML::link(URL::to('account/view_title/' . $title->title_id), $title->title_name . '（'. $title->teacher()->count() . '）') }}</li>
				@endforeach
			@endif
		</ul>

		<div id="title_form_command">
			@if ($titleId >= 1)
				{{ Form::text('title_name', Title::find($titleId)->title_name, array('required' => 'required', 'placeholder' => '新增職稱…')) }}
				{{ Form::submit('更新', array('id' => 'add_title')) }}
				{{ HtmlComposite::delete('account/delete_title/' . $titleId, '刪除《' . Title::find($titleId)->title_name . '》') }}
			@else
				{{ Form::text('title_name', '', array('required' => 'required', 'placeholder' => '新增職稱…')) }}
				{{ Form::submit('新增', array('id' => 'add_title')) }}
			@endif
		</div>
		{{ Form::close() }}

	</div>

	@if (isset($teacherList))
		<table class="data_table table_style_1">
		    <tr>
		        <th class="teacher_name">姓名</th>
		        <th class="teacher_account">帳號</th>
		        <th class="title">職稱</th>
		        <th colspan="2" class="add">{{ HtmlComposite::add('account/add') }}</th>
		    </tr>
		    @foreach ($teacherList as $teacher)
			    <tr>
			        <td>{{ $teacher->teacher_name }}</td>
			        <td>{{ $teacher->teacher_account }}</td>
			        <td class="title">
			        	@if ($teacher->title_id == 0)
			        		無職稱
			        	@else
			        		<?php
			        			try {
									echo $teacher->title()->first()->title_name;
			        			}catch(Exception $e){
									echo '<div class="alert">查詢錯誤！</div>';
			        			}
			        		?>

			        	@endif
			        	</td>
			        <td class="edit">{{ HtmlComposite::edit('account/edit/' . $teacher->teacher_id) }}</td>
			        <td class="delete">{{ HtmlComposite::delete('account/delete/' . $teacher->teacher_id) }}</td>
			    </tr>
		    @endforeach
		</table>
	@endif
@stop