<?php
Asset::add('form', 'css/form.css');
Asset::add('form', 'css/course_time_selector.css');
Asset::add('table1', 'css/table_style1.css');
?>
<h1>班級、年級管理</h1>

<?= HtmlComposite::messageBlock() ?>

<div id="year">
<?= OstForm::open(URL::to('class_year/add_year')) ?>
<?= OstForm::text('year_name', '年級', Input::old('year_name'), array('required' => 'required')) ?>
<?= OstForm::submit('新增年級') ?>
<?= OstForm::close() ?>
</div>


<div id="class">

</div>

<div id="year_course_time_selector">
	<table>
		<tr id="day_row">
			<th>&nbsp;</th>
			<th>週一</th>
			<th>週二</th>
			<th>週三</th>
			<th>週四</td>
			<th>週五</th>
		</tr>
		<tr>
			<td class="course_column">第一節</td>
			<td id="course_1" data-selected="0">&nbsp;</td>
			<td id="course_8" data-selected="0">&nbsp;</td>
			<td id="course_15" data-selected="0">&nbsp;</td>
			<td id="course_22" data-selected="0">&nbsp;</td>
			<td id="course_29" data-selected="0">&nbsp;</td>
		</tr>
		<tr>
			<td class="course_column">第二節</td>
			<td id="course_2" data-selected="0">&nbsp;</td>
			<td id="course_9" data-selected="0">&nbsp;</td>
			<td id="course_16" data-selected="0">&nbsp;</td>
			<td id="course_23" data-selected="0">&nbsp;</td>
			<td id="course_30" data-selected="0">&nbsp;</td>
		</tr>
		<tr>
			<td class="course_column">第三節</td>
			<td id="course_3" data-selected="0">&nbsp;</td>
			<td id="course_10" data-selected="0">&nbsp;</td>
			<td id="course_16" data-selected="0">&nbsp;</td>
			<td id="course_24" data-selected="0">&nbsp;</td>
			<td id="course_31" data-selected="0">&nbsp;</td>
		</tr>
		<tr>
			<td class="course_column">第四節</td>
			<td id="course_4" data-selected="0">&nbsp;</td>
			<td id="course_11" data-selected="0">&nbsp;</td>
			<td id="course_17" data-selected="0">&nbsp;</td>
			<td id="course_25" data-selected="0">&nbsp;</td>
			<td id="course_32" data-selected="0">&nbsp;</td>
		</tr>
		<tr>
			<td id="noon_break" colspan="6">午休</td>
		</tr>
		<tr>
			<td class="course_column">第五節</td>
			<td id="course_5" data-selected="0">&nbsp;</td>
			<td id="course_12" data-selected="0">&nbsp;</td>
			<td id="course_18" data-selected="0">&nbsp;</td>
			<td id="course_26" data-selected="0">&nbsp;</td>
			<td id="course_33" data-selected="0">&nbsp;</td>
		</tr>
		<tr>
			<td class="course_column">第六節</td>
			<td id="course_6" data-selected="0">&nbsp;</td>
			<td id="course_13" data-selected="0">&nbsp;</td>
			<td id="course_19" data-selected="0">&nbsp;</td>
			<td id="course_27" data-selected="0">&nbsp;</td>
			<td id="course_34" data-selected="0">&nbsp;</td>
		</tr>
		<tr>
			<td class="course_column">第七節</td>
			<td id="course_7" data-selected="0">&nbsp;</td>
			<td id="course_14" data-selected="0">&nbsp;</td>
			<td id="course_21" data-selected="0">&nbsp;</td>
			<td id="course_28" data-selected="0">&nbsp;</td>
			<td id="course_35" data-selected="0">&nbsp;</td>
		</tr>
	</table>
</div>

<?php if (isset($teacherList)): ?>
	<table>
	    <tr>
	        <th class="teacher_name">姓名</th>
	        <th class="teacher_account">帳號</th>
	        <th class="edit">&nbsp;</th>
	        <th class="delete">&nbsp;</th>
	    </tr>
	    <?php foreach ($teacherList as $teacher): ?>
		    <tr>
		        <td><?= $teacher->teacher_name ?></td>
		        <td><?= $teacher->teacher_account ?></td>
		        <td class="edit"><?= HtmlComposite::edit('account/edit/' . $teacher->teacher_id) ?></td>
		        <td class="delete"><?= HtmlComposite::delete('account/delete/' . $teacher->teacher_id) ?></td>
		    </tr>
	    <?php endforeach ?>
	</table>
<?php endif ?>