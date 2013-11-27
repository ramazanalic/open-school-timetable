@extends('layouts.default')

@section('css')
	{{ HTML::style('css/form/account_form.css') }}
@stop

@section('content')
	{{ HtmlComposite::back('account') }}

	{{ (isset($teacher)) ? '<h1>編輯教師《' . $teacher->teacher_name . '》</h1>' : '<h1>新增教師</h1>'}}

	{{ HtmlComposite::messageBlock() }}

	{{ OstForm::open($teacher, (isset($teacher)) ? 'account/edit/' . $teacher->teacher_id : 'account/add') }}
	{{ OstForm::description('請輸入以下的資料') }}
	{{ OstForm::text('teacher_name', '教師姓名', array('autofocus' => 'autofocus', 'required' => 'required')) }}
	{{ OstForm::text('teacher_account', '帳號', array('placeholder' => '英文+數字', 'required' => 'required')) }}
	{{ OstForm::select('title_id', '職稱', array('valueArray' => array('0' => '無職稱'), 'required' => 'required'), array('Title', 'title_id', 'title_name')) }}
	{{ OstForm::password('teacher_password', '密碼') }}
	{{ OstForm::password('teacher_password_confirmation', '確認密碼') }}
	{{ (isset($teacher)) ? OstForm::submit('更新') : OstForm::submit('新增') }}
	{{ OstForm::close() }}
@stop
