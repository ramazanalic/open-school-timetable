<?php Asset::add('global', 'css/form.css'); ?>
<?php Asset::add('login', 'css/login.css'); ?>
<h1>使用者登入</h1>
<?= OSTForm::open(URL::to('login')) ?>
<?= OSTForm::text('name', '帳號', NULL, array('autofocus' => 'autofocus')) ?>
<?= OSTForm::password('password', '密碼') ?>
<?= OSTForm::submit('登入') ?>
<?= OSTForm::close() ?>
