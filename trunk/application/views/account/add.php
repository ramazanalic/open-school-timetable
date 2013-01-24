<?php Asset::add('global', 'css/form.css') ?>
<?php Asset::add('account_add', 'css/account_add.css') ?>
<?= HtmlComposite::back('account') ?>
<h1>新增教師</h1>
<?= OstForm::open(URL::to('account/add')) ?>
<?= OstForm::text('name', '教師姓名', NULL, array('autofocus' => 'autofocus')) ?>
<?= OstForm::text('account', '帳號', NULL, array('placeholder' => '請使用英文+數字的組合')) ?>
<?= OstForm::password('password', '密碼') ?>
<?= OstForm::password('password_confirm', '確認密碼') ?>
<?= OstForm::submit('新增') ?>
<?= OstForm::close() ?>
