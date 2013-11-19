@extends('layouts.default')

@section('css')
	{{ HTML::style('css/table/table_style1.css') }}
	{{ HTML::style('css/account_index.css') }}
@stop

@section('content')
	<h1>教師列表</h1>

	{{ HtmlComposite::messageBlock() }}

	<div id="title_list">
		{{ Form::open(array('url' => URL::to('account/add_title/'))) }}
		<ul>
			<li>{{ HTML::link(URL::to('account'), '全部') }}</li>
			<li>{{ HTML::link(URL::to('account/view_title/0'), '未分類') }}</li>

			@if (isset($titleList))
				@foreach ($titleList as $title)
					<li>{{ HTML::link(URL::to('account/view_title/' . $title->title_id), $title->title_name . '('. $title->teacher()->count() . ')') }}</li>
				@endforeach
			@endif
			<li>
				{{ Form::text('title_name', '', array('required' => 'required', 'placeholder' => '新增職稱…')) }}
				{{ Form::submit('新增', array('id' => 'add_title')) }}
			</li>
		</ul>
		{{ Form::close() }}

	</div>

	@if (isset($teacherList))
		<table class="dataList">
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
			        <td class="title"></td>
			        <td class="edit">{{ HtmlComposite::edit('account/edit/' . $teacher->teacher_id) }}</td>
			        <td class="delete">{{ HtmlComposite::delete('account/delete/' . $teacher->teacher_id) }}</td>
			    </tr>
		    @endforeach
		</table>
	@endif
@stop