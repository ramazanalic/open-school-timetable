@extends('layouts.default')

@section('css')
	{{ HTML::style('css/form/account_form.css') }}
@stop

@section('content')
	{{ HtmlComposite::back('account') }}

	{{ (isset($teacher)) ? '<h1>編輯教師《' . $teacher->teacher_name . '》</h1>' : '<h1>新增教師</h1>'}}

	{{ HtmlComposite::messageBlock() }}

	{{ FormList::open($teacher, (isset($teacher)) ? 'account/edit/' . $teacher->teacher_id : 'account/add') }}
	{{ FormList::description('請輸入以下的資料') }}
	{{ FormList::text('teacher_name', '教師姓名', array('autofocus' => 'autofocus', 'required' => 'required')) }}
	{{ FormList::text('teacher_account', '帳號', array('placeholder' => '英文+數字', 'required' => 'required')) }}
	{{ FormList::select('title_id', '職稱', array('valueArray' => array('0' => '無職稱'), 'required' => 'required'), array('Title', 'title_id', 'title_name')) }}
	{{ FormList::select('teacher_course_count', '上課節數', array('range' => array(1, 25))) }}
	{{ FormList::password('teacher_password', '密碼') }}
	{{ FormList::password('teacher_password_confirmation', '確認密碼') }}
	
	{{ (isset($teacher)) ? FormList::submit('更新') : FormList::submit('新增') }}
	{{ FormList::close() }}
@stop
