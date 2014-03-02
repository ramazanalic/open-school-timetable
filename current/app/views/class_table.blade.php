@extends('layouts.default')

@section('css')

@stop

<?php View::share('titlePrefix', '班級課表查詢'); ?>

@section('content')
	<h1>班級課表查詢</h1>
	
	{{ HtmlComposite::messageBlock() }}
@stop
