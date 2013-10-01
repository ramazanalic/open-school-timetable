@extends('layouts.default')

@section('css')
	{{ HTML::style('css/form/account_form.css') }}
@stop

@section('content')
	{{ HtmlComposite::back('account') }}

	@if ($formType == 'add')
		<h1>新增教師</h1>
	@elseif ($formType == 'edit')
		<h1>編輯教師《{{ $teacher->teacher_name }} 》</h1>
	@endif

	{{ HtmlComposite::messageBlock() }}

	@if ($formType == 'add')
		{{ OstForm::open($name = NULL, 'account/add') }}
	@elseif ($formType == 'edit')
		{{ OstForm::open($teacher, 'account/edit/' . $teacher->teacher_id) }}
	@endif

	{{ OstForm::description('請輸入以下的資料') }}
	{{ OstForm::text('teacher_name', '教師姓名', array('autofocus' => 'autofocus', 'required' => 'required')) }}
	{{ OstForm::text('teacher_account', '帳號', array('placeholder' => '英文+數字', 'required' => 'required')) }}

	if ($formType == 'edit')
		{{ OstForm::open($teacher, 'account/edit/' . $teacher->teacher_id) }}
	@endif

	{{ (isset($editTeacherForm)) ?  : '' }}
	{{ OstForm::password('teacher_password', '密碼') }}
	{{ OstForm::password('teacher_password_confirmation', '確認密碼') }}

	@if ($formType == 'add')
		{{ OstForm::submit('新增') }}
	@elseif ($formType == 'edit')
		{{ OstForm::submit('更新') }}
	@endif

	{{ OstForm::close() }}
@stop
