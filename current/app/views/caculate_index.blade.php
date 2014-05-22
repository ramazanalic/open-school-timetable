@extends('layouts.default')

@section('css')	
	{{ HTML::style('css/form/caculate.css') }}
	{{ HTML::style('css/caculate_index.css') }}
@stop

@section('js')
	{{ HTML::script('js/extinction_toggle.js') }}
@stop

<?php View::share('titlePrefix', '計算課表'); ?>

@section('content')
	<h1>計算課表</h1>
	
	{{ HtmlComposite::messageBlock() }}

	{{ FormList::open('' , URL::to('caculate')) }}		
		{{ FormList::select('seedCount', '計算複雜度：', array('range' => array(1, 5))) }}
		<br>
		{{ FormList::checkbox('extinction', '使用粒子活化機制', 1) }}		
		{{ FormList::select('extinction_time', '活化程度：', array('range' => array(1, 5))) }}
		<br>
		{{ FormList::submit('計算') }}
	{{ FormList::close() }}	
	
	@if (Session::has('seedProgressHistory'))
		<?php $seedProgressHistory = Session::get('seedProgressHistory') ?>
		@for ($i = 0, $j = 0; $i < count($seedProgressHistory); $i++)
			@if (substr($seedProgressHistory[$i], 0, 3) == 'ext')
				<ul class="progress_history">
					<li class="title">{{ $seedProgressHistory[$i] }}</li>
					@for (; $j < $i; $j++)
						<li class="item">{{ $seedProgressHistory[$j] }}</li>
					@endfor
					<?php $j++ ?>
				</ul>
			@endif
		@endfor				
	@endif
@stop