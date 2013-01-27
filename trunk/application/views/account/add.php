<?php
Asset::add('global', 'css/form.css');
Asset::add('account_add', 'css/account_add.css');
$GLOBALS['errors'] = $errors;
?>

<?= HtmlComposite::back('account') ?>
<h1>新增教師</h1>
<?= HtmlComposite::messageBlock() ?>
<?= OstForm::open(URL::to('account/add')) ?>
<?= OstForm::description('請輸入以下的資料') ?>
<?= OstForm::text('name', '教師姓名', Input::old('name'), array(
		'autofocus' => 'autofocus',
		'required' => 'required'
	))
?>
<?= OstForm::text('account', '帳號', Input::old('account'), array(
		'placeholder' => '英文+數字',
		'required' => 'required'
	))
?>
<?= OstForm::password('password', '密碼') ?>
<?= OstForm::password('password_confirmation', '確認密碼') ?>
<?= OstForm::submit('新增') ?>
<?= OstForm::close() ?>
