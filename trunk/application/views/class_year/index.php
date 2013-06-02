<?php
Asset::add('form', 'css/form.css');
Asset::add('course_time', 'css/course_time_selector.css');
Asset::add('year_class', 'css/year_class.css');
Asset::add('table1', 'css/table_style1.css');
Asset::add('selector', 'js/year_course_selector.js');
?>
<h1>班級、年級管理</h1>

<?= HtmlComposite::messageBlock() ?>

<div id="year_row">
	<?php if (isset($yearList)): ?>
		<?php foreach ($yearList as $year): ?>
			<a class="year_item" href="view_year/id/<?= $year->year_id ?>"><?= $year->year_name ?></a>
		<?php endforeach; ?>
	<?php endif; ?>

	<?= HtmlComposite::add('class_year/add_year', '新增年級', array('class' => 'add_button year_item')) ?>
</div>

<div id="class_area">
</div>

<div id="command_area">
	<?php if (isset($showYearForm)): ?>
		<div id="year_form">
			<h1>新增年級</h1>
			<?= OstForm::open(URL::to('class_year/add_year')) ?>
			<?= OstForm::text('year_name', '年級', Input::old('year_name'), array('required' => 'required')) ?>
			<?= OstForm::submit('新增年級', array('id', 'add_year')); ?>
			<?= OstForm::hidden('course_time', '') ?>
			<?= OstForm::close() ?>
		</div>

		<div id="year_course_time_selector">
			<h1>年級上課時間設定</h1>
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
					<td class="course" id="course_1" data-selected="0">&nbsp;</td>
					<td class="course" id="course_8" data-selected="0">&nbsp;</td>
					<td class="course" id="course_15" data-selected="0">&nbsp;</td>
					<td class="course" id="course_22" data-selected="0">&nbsp;</td>
					<td class="course" id="course_29" data-selected="0">&nbsp;</td>
				</tr>
				<tr>
					<td class="course_column">第二節</td>
					<td class="course" id="course_2" data-selected="0">&nbsp;</td>
					<td class="course" id="course_9" data-selected="0">&nbsp;</td>
					<td class="course" id="course_16" data-selected="0">&nbsp;</td>
					<td class="course" id="course_23" data-selected="0">&nbsp;</td>
					<td class="course" id="course_30" data-selected="0">&nbsp;</td>
				</tr>
				<tr>
					<td class="course_column">第三節</td>
					<td class="course" id="course_3" data-selected="0">&nbsp;</td>
					<td class="course" id="course_10" data-selected="0">&nbsp;</td>
					<td class="course" id="course_17" data-selected="0">&nbsp;</td>
					<td class="course" id="course_24" data-selected="0">&nbsp;</td>
					<td class="course" id="course_31" data-selected="0">&nbsp;</td>
				</tr>
				<tr>
					<td class="course_column">第四節</td>
					<td class="course" id="course_4" data-selected="0">&nbsp;</td>
					<td class="course" id="course_11" data-selected="0">&nbsp;</td>
					<td class="course" id="course_18" data-selected="0">&nbsp;</td>
					<td class="course" id="course_25" data-selected="0">&nbsp;</td>
					<td class="course" id="course_32" data-selected="0">&nbsp;</td>
				</tr>
				<tr>
					<td id="noon_break" colspan="6">午休</td>
				</tr>
				<tr>
					<td class="course_column">第五節</td>
					<td class="course" id="course_5" data-selected="0">&nbsp;</td>
					<td class="course" id="course_12" data-selected="0">&nbsp;</td>
					<td class="course" id="course_19" data-selected="0">&nbsp;</td>
					<td class="course" id="course_26" data-selected="0">&nbsp;</td>
					<td class="course" id="course_33" data-selected="0">&nbsp;</td>
				</tr>
				<tr>
					<td class="course_column">第六節</td>
					<td class="course" id="course_6" data-selected="0">&nbsp;</td>
					<td class="course" id="course_13" data-selected="0">&nbsp;</td>
					<td class="course" id="course_20" data-selected="0">&nbsp;</td>
					<td class="course" id="course_27" data-selected="0">&nbsp;</td>
					<td class="course" id="course_34" data-selected="0">&nbsp;</td>
				</tr>
				<tr>
					<td class="course_column">第七節</td>
					<td class="course" id="course_7" data-selected="0">&nbsp;</td>
					<td class="course" id="course_14" data-selected="0">&nbsp;</td>
					<td class="course" id="course_21" data-selected="0">&nbsp;</td>
					<td class="course" id="course_28" data-selected="0">&nbsp;</td>
					<td class="course" id="course_35" data-selected="0">&nbsp;</td>
				</tr>
			</table>
		</div>
	<?php endif; ?>
</div>