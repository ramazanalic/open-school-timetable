@extends('layouts.default')

@section('css')
	{{ HTML::style('css/table/style_1.css') }}
	{{ HTML::style('css/row_item/style_1.css') }}	
	{{ HTML::style('css/form/title.css') }}
	{{ HTML::style('css/account.css') }}
@stop

<?php View::share('titlePrefix', '帳號管理') ?>
<?php View::share('selectUrl', 'account'); ?>

@section('content')	
	<h1>帳號管理</h1>

	{{ Helper::message() }}

	<div class="row_item row_item_style_1" id="title_list">
		<ul>				
			<li {{ ($titleId == 'all') ? 'class="row_item_selected"' : '' }}>{{ link_to('account', '全部（' . count(Teacher::All()) . '）') }}</li>
			<li {{ ($titleId == '0') ? 'class="row_item_selected"' : '' }}>{{ link_to('account/view_title/0', '無職稱（' . Teacher::where('title_id', '=', 0)->count() . '）') }}</li>
	
			@if (isset($titleList))
				@foreach ($titleList as $title)
					<li {{ ($titleId == $title->title_id) ? 'class="row_item_selected"' : '' }}>{{ link_to('account/view_title/' . $title->title_id, $title->title_name . '（'. $title->teacher()->count() . '）') }}</li>
				@endforeach
			@endif
		</ul>
	</div>
	
	<div id="title_form_command">				
		{{ FormList::open(Title::find($titleId), (Title::find($titleId)) ? 'account/update_title/' . $titleId : 'account/add_title/') }}		
		{{ FormList::text('title_name', '職稱', array('required' => 'required')) }}					
		
		@if (Title::find($titleId))
			{{ FormList::submit('更新職稱') . Helper::delete('account/delete_title/' . $titleId, '刪除《' . Title::find($titleId)->title_name . '》職稱') }}
		@else
			{{ FormList::submit('新增職稱') }}
		@endif
				
		{{ Form::close() }}									
	</div>
	
	<table class="data_table table_style_1">
	    <tr>
	        <th class="teacher_name">姓名</th>
	        <th class="teacher_account">帳號</th>		        
	        <th class="title">職稱</th>
	        <th class="classes">導師班</th>
	        <th class="teacher_course_count">應上節數</th>
	        <th class="is_admin"></th>
	        <th class="command">{{ Helper::add('account/add/' . $titleId, '新增教師') }}</th>
	    </tr>
		    
		@if ($teacherList->count() != 0)
		    @foreach ($teacherList as $teacher)
			    <tr>
			        <td class="teacher_name">{{ $teacher->teacher_name }}</td>
			        <td class="teacher_account">{{ $teacher->teacher_account }}</td>
			        <td class="title">
			        	@if ($teacher->title_id == 0)
			        		無職稱
			        	@else
			        		<?php
			        			try {
									echo $teacher->title->title_name;
			        			}catch(Exception $e){
									echo '<div class="alert">查詢錯誤！</div>';
			        			}
			        		?>
			        	@endif
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
			        <td class="teacher_course_count">{{ $teacher->teacher_course_count }}</td>
			        <td class="is_admin">{{ ($teacher->teacher_privilege == 2) ? '<div>管理者</div>' : '' }}</td>
			        <td class="command">
			        	{{ Helper::edit('account/edit/' . $teacher->teacher_id . '/titleId/' . $titleId) }}
			        	{{ Helper::delete('account/delete/' . $teacher->teacher_id) }}
			        </td>			        
			    </tr>
		    @endforeach
		@endif
	</table>	
@stop