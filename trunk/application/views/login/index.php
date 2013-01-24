<?php Asset::add('global', 'css/form.css'); ?>

<h1>使用者登入</h1>
<?= OstForm::open(URL::to('login')) ?>
<?= OstForm::description('請輸入帳號密碼') ?>
<?= OstForm::text('name', '帳號', NULL, array('autofocus' => 'autofocus')) ?>
<?= OstForm::password('password', '密碼') ?>
<?= OstForm::submit('登入') ?>
<?= OstForm::close() ?>
