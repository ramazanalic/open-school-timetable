<?php
OstForm::setErrors($errors);
Asset::add('form', 'css/form.css');
Asset::add('account_add', 'css/account_form.css');

// 處理表單資料
if (isset($teacher)) {
	$formData['teacher_name'] = $teacher->teacher_name;
	$formData['teacher_account'] = $teacher->teacher_account;
} else {
	$formData['teacher_name'] = Input::old('teacher_name');
	$formData['teacher_account'] = Input::old('teacher_account');
}
?>

<?= HtmlComposite::back('account') ?>

<?php
if ($type == 'add') {
	echo '<h1>新增教師</h1>';
} else {
	echo '<h1>編輯教師《' . $teacher->teacher_name . '》</h1>';
}
?>

<?= HtmlComposite::messageBlock() ?>

<?= OstForm::open(URL::to('account/' . $type)) ?>
<?= OstForm::description('請輸入以下的資料') ?>
<?= OstForm::text('teacher_name', '教師姓名', $formData['teacher_name'], array(
		'autofocus' => 'autofocus',
		'required' => 'required'
	))
?>
<?= OstForm::text('teacher_account', '帳號', $formData['teacher_account'], array(
		'placeholder' => '英文+數字',
		'required' => 'required'
	))
?>
<?= OstForm::password('teacher_password', '密碼') ?>
<?= OstForm::password('teacher_password_confirmation', '確認密碼') ?>

<?php
if ($type == 'add') {
	echo OstForm::submit('新增');
} else {
	echo OstForm::submit('更新');
}
?>

<?= OstForm::close() ?>
