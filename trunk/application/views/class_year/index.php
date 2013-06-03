<?php
Asset::add('form', 'css/form.css');
Asset::add('course_time', 'css/course_time_selector.css');
Asset::add('year_class', 'css/year_class.css');
Asset::add('table1', 'css/table_style1.css');
Asset::add('selector', 'js/year_course_selector.js');

// 設定表單資料
OstForm::setData((isset($year) && !Input::old()) ? $year->to_array() : Input::old());
?>

<h1>班級、年級管理</h1>

<?= HtmlComposite::messageBlock() ?>

<div id="year_row">
    <?php if (isset($yearList)): ?>
    	<?php foreach ($yearList as $yearItem): ?>
    		<?= HTML::link(URL::to('class_year/view_year/' . $yearItem->year_id), $year->year_name, array('class' => 'year_item')) ?>
    	<?php endforeach; ?>
    <?php endif; ?>

    <?= HtmlComposite::add('class_year/add_year', '新增年級', array('class' => 'add_button year_item')) ?>
</div>

<div id="class_area"></div>

<div id="command_area">
    <?php if (isset($showYearForm)): ?>
    <div id="year_form">
        <h1><?= (isset($editYearForm)) ? '更新《' . $year->year_name . '》' : '新增年級' ?></h1>
        <?= OstForm::open(URL::to((isset($editYearForm)) ? 'class_year/edit_year/' . $year->year_id : 'class_year/add_year')) ?>
        <?= OstForm::text('year_name', '年級名稱', array('required' => 'required')) ?>
        <?= OstForm::submit((isset($editYearForm)) ? '更新' : '新增', array('id', 'add_year')); ?>
        <?= OstForm::hidden('course_time') ?>
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
                <td class="course" id="course_1" data-selected="<?= substr(OstForm::getData('course_time'), 0, 1) ?>">&nbsp;</td>
                <td class="course" id="course_8" data-selected="<?= substr(OstForm::getData('course_time'), 7, 1) ?>">&nbsp;</td>
                <td class="course" id="course_15" data-selected="<?= substr(OstForm::getData('course_time'), 14, 1) ?>">&nbsp;</td>
                <td class="course" id="course_22" data-selected="<?= substr(OstForm::getData('course_time'), 21, 1) ?>">&nbsp;</td>
                <td class="course" id="course_29" data-selected="<?= substr(OstForm::getData('course_time'), 28, 1) ?>">&nbsp;</td>
            </tr>
            <tr>
                <td class="course_column">第二節</td>
                <td class="course" id="course_2" data-selected="<?= substr(OstForm::getData('course_time'), 1, 1) ?>">&nbsp;</td>
                <td class="course" id="course_9" data-selected="<?= substr(OstForm::getData('course_time'), 8, 1) ?>">&nbsp;</td>
                <td class="course" id="course_16" data-selected="<?= substr(OstForm::getData('course_time'), 15, 1) ?>">&nbsp;</td>
                <td class="course" id="course_23" data-selected="<?= substr(OstForm::getData('course_time'), 22, 1) ?>">&nbsp;</td>
                <td class="course" id="course_30" data-selected="<?= substr(OstForm::getData('course_time'), 29, 1) ?>">&nbsp;</td>
            </tr>
            <tr>
                <td class="course_column">第三節</td>
                <td class="course" id="course_3" data-selected="<?= substr(OstForm::getData('course_time'), 2, 1) ?>">&nbsp;</td>
                <td class="course" id="course_10" data-selected="<?= substr(OstForm::getData('course_time'), 9, 1) ?>">&nbsp;</td>
                <td class="course" id="course_17" data-selected="<?= substr(OstForm::getData('course_time'), 16, 1) ?>">&nbsp;</td>
                <td class="course" id="course_24" data-selected="<?= substr(OstForm::getData('course_time'), 23, 1) ?>">&nbsp;</td>
                <td class="course" id="course_31" data-selected="<?= substr(OstForm::getData('course_time'), 30, 1) ?>">&nbsp;</td>
            </tr>
            <tr>
                <td class="course_column">第四節</td>
                <td class="course" id="course_4" data-selected="<?= substr(OstForm::getData('course_time'), 3, 1) ?>">&nbsp;</td>
                <td class="course" id="course_11" data-selected="<?= substr(OstForm::getData('course_time'), 10, 1) ?>">&nbsp;</td>
                <td class="course" id="course_18" data-selected="<?= substr(OstForm::getData('course_time'), 17, 1) ?>">&nbsp;</td>
                <td class="course" id="course_25" data-selected="<?= substr(OstForm::getData('course_time'), 24, 1) ?>">&nbsp;</td>
                <td class="course" id="course_32" data-selected="<?= substr(OstForm::getData('course_time'), 31, 1) ?>">&nbsp;</td>
            </tr>
            <tr>
                <td id="noon_break" colspan="6">午休</td>
            </tr>
            <tr>
                <td class="course_column">第五節</td>
                <td class="course" id="course_5" data-selected="<?= substr(OstForm::getData('course_time'), 4, 1) ?>">&nbsp;</td>
                <td class="course" id="course_12" data-selected="<?= substr(OstForm::getData('course_time'), 11, 1) ?>">&nbsp;</td>
                <td class="course" id="course_19" data-selected="<?= substr(OstForm::getData('course_time'), 18, 1) ?>">&nbsp;</td>
                <td class="course" id="course_26" data-selected="<?= substr(OstForm::getData('course_time'), 25, 1) ?>">&nbsp;</td>
                <td class="course" id="course_33" data-selected="<?= substr(OstForm::getData('course_time'), 32, 1) ?>">&nbsp;</td>
            </tr>
            <tr>
                <td class="course_column">第六節</td>
                <td class="course" id="course_6" data-selected="<?= substr(OstForm::getData('course_time'), 5, 1) ?>">&nbsp;</td>
                <td class="course" id="course_13" data-selected="<?= substr(OstForm::getData('course_time'), 12, 1) ?>">&nbsp;</td>
                <td class="course" id="course_20" data-selected="<?= substr(OstForm::getData('course_time'), 19, 1) ?>">&nbsp;</td>
                <td class="course" id="course_27" data-selected="<?= substr(OstForm::getData('course_time'), 26, 1) ?>">&nbsp;</td>
                <td class="course" id="course_34" data-selected="<?= substr(OstForm::getData('course_time'), 33, 1) ?>">&nbsp;</td>
            </tr>
            <tr>
                <td class="course_column">第七節</td>
                <td class="course" id="course_7" data-selected="<?= substr(OstForm::getData('course_time'), 6, 1) ?>">&nbsp;</td>
                <td class="course" id="course_14" data-selected="<?= substr(OstForm::getData('course_time'), 13, 1) ?>">&nbsp;</td>
                <td class="course" id="course_21" data-selected="<?= substr(OstForm::getData('course_time'), 20, 1) ?>">&nbsp;</td>
                <td class="course" id="course_28" data-selected="<?= substr(OstForm::getData('course_time'), 27, 1) ?>">&nbsp;</td>
                <td class="course" id="course_35" data-selected="<?= substr(OstForm::getData('course_time'), 34, 1) ?>">&nbsp;</td>
            </tr>
        </table>
    </div>
    <?php endif; ?>
</div>