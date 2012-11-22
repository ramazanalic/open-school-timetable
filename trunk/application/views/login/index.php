<h1>登入畫面</h1>
<?= OSTForm::open(URL::to('login')) ?>
<?= OSTForm::textLine('name', '帳號') ?>
<?= OSTForm::close() ?>