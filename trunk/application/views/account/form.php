<?php
OstForm::setErrors($errors);
Asset::add('form', 'css/form.css');
Asset::add('account_form', 'css/account_form.css');

// 設定表單資料
OstForm::setData((isset($teacher) && !Input::old()) ? $teacher->to_array() : Input::old());
?>

<?= HtmlComposite::back('account') ?>

<?= (isset($editTeacherForm)) ? '<h1>編輯教師《' . $teacher->teacher_name . '》</h1>' : '<h1>新增教師</h1>' ?>

<?= HtmlComposite::messageBlock() ?>

<?= OstForm::open(URL::to((isset($editTeacherForm)) ? 'account/edit/' . $teacher->teacher_id : 'account/add')) ?>
<?= OstForm::description('請輸入以下的資料') ?>
<?= OstForm::text('teacher_name', '教師姓名', array('autofocus' => 'autofocus', 'required' => 'required')) ?>
<?= OstForm::text('teacher_account', '帳號', array('placeholder' => '英文+數字', 'required' => 'required')) ?>
<?= (isset($editTeacherForm)) ? OstForm::hidden('teacher_id') : '' ?>
<?= OstForm::password('teacher_password', '密碼') ?>
<?= OstForm::password('teacher_password_confirmation', '確認密碼') ?>

<?= (isset($editTeacherForm)) ? OstForm::submit('更新') : OstForm::submit('新增') ?>

<?= OstForm::close() ?>
