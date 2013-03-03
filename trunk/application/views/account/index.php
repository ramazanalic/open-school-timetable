<?php
Asset::add('table1', 'css/table_style1.css');
?>
<h1>教師列表</h1>
<?= HtmlComposite::add('account/add') ?>
<?= HtmlComposite::messageBlock() ?>

<?php if ($teacherList): ?>
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