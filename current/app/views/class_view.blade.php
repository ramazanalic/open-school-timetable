@extends('layouts.default')

@section('css')
{{ HTML::style('css/table/table_style_1.css') }}
{{ HTML::style('css/class_year.css') }}
{{ HTML::style('css/js/course_time_selector.css') }}
{{ HTML::style('css/form/class_year_form.css') }}
{{ HTML::style('css/class_view.css') }}
@stop

@section('content')
<h1>班級課表查詢</h1>
<?php View::share('titlePrefix','班級課表查詢')
?>

<div id="year_row">
    <a class="year_item" href="http://127.0.0.1/class_year/view_year/3">一年級（11）</a><a class="year_item" href="http://127.0.0.1/class_year/view_year/6">三年級（12）</a><a class="year_item" href="http://127.0.0.1/class_year/view_year/4">二年級（11）</a><a class="year_item" href="http://127.0.0.1/class_year/view_year/2">五年級（13）</a><a class="year_item" href="http://127.0.0.1/class_year/view_year/10">六年級（12）</a><a class="year_item" href="http://127.0.0.1/class_year/view_year/1">四年級（11）</a>
</div>

<div id="class_area">
    <form accept-charset="UTF-8" action="http://127.0.0.1/class_year/add_classes/3" method="POST">
        <input type="hidden" value="DoXFDnPf1XrqEWRV8fIpRntOyJgr4go1lo760cAV" name="_token">
        <table class="data_table table_style_1">
            <tbody>
                <tr>
                    <th class="classes_name">
                    <input type="text" value="" name="classes_name" autofocus="autofocus" placeholder="新增班級…" required="required">
                    </th>
                    <th class="classes_command">
                    <input type="submit" value="新增" id="add_classes">
                    </th>
                </tr>
            </tbody>
        </table>
    </form>
    <form accept-charset="UTF-8" action="http://127.0.0.1/class_year/update_classes/4/3" method="POST">
        <input type="hidden" value="DoXFDnPf1XrqEWRV8fIpRntOyJgr4go1lo760cAV" name="_token">
        <table class="data_table table_style_1">
            <tbody>
                <tr>
                    <td class="classes_name">
                    <input type="text" value="101" name="classes_name" size="5" required="required">
                    </td>
                    <td class="classes_command">
                    <input type="submit" value="更新">
                    <a class="delete_link" href="http://127.0.0.1/class_year/delete_classes/4/3">刪除</a></td>
                </tr>
            </tbody>
        </table>
    </form>
    <form accept-charset="UTF-8" action="http://127.0.0.1/class_year/update_classes/10/3" method="POST">
        <input type="hidden" value="DoXFDnPf1XrqEWRV8fIpRntOyJgr4go1lo760cAV" name="_token">
        <table class="data_table table_style_1">
            <tbody>
                <tr>
                    <td class="classes_name">
                    <input type="text" value="102" name="classes_name" size="5" required="required">
                    </td>
                    <td class="classes_command">
                    <input type="submit" value="更新">
                    <a class="delete_link" href="http://127.0.0.1/class_year/delete_classes/10/3">刪除</a></td>
                </tr>
            </tbody>
        </table>
    </form>
    <form accept-charset="UTF-8" action="http://127.0.0.1/class_year/update_classes/11/3" method="POST">
        <input type="hidden" value="DoXFDnPf1XrqEWRV8fIpRntOyJgr4go1lo760cAV" name="_token">
        <table class="data_table table_style_1">
            <tbody>
                <tr>
                    <td class="classes_name">
                    <input type="text" value="103" name="classes_name" size="5" required="required">
                    </td>
                    <td class="classes_command">
                    <input type="submit" value="更新">
                    <a class="delete_link" href="http://127.0.0.1/class_year/delete_classes/11/3">刪除</a></td>
                </tr>
            </tbody>
        </table>
    </form>
    <form accept-charset="UTF-8" action="http://127.0.0.1/class_year/update_classes/12/3" method="POST">
        <input type="hidden" value="DoXFDnPf1XrqEWRV8fIpRntOyJgr4go1lo760cAV" name="_token">
        <table class="data_table table_style_1">
            <tbody>
                <tr>
                    <td class="classes_name">
                    <input type="text" value="104" name="classes_name" size="5" required="required">
                    </td>
                    <td class="classes_command">
                    <input type="submit" value="更新">
                    <a class="delete_link" href="http://127.0.0.1/class_year/delete_classes/12/3">刪除</a></td>
                </tr>
            </tbody>
        </table>
    </form>
    <form accept-charset="UTF-8" action="http://127.0.0.1/class_year/update_classes/13/3" method="POST">
        <input type="hidden" value="DoXFDnPf1XrqEWRV8fIpRntOyJgr4go1lo760cAV" name="_token">
        <table class="data_table table_style_1">
            <tbody>
                <tr>
                    <td class="classes_name">
                    <input type="text" value="105" name="classes_name" size="5" required="required">
                    </td>
                    <td class="classes_command">
                    <input type="submit" value="更新">
                    <a class="delete_link" href="http://127.0.0.1/class_year/delete_classes/13/3">刪除</a></td>
                </tr>
            </tbody>
        </table>
    </form>
    <form accept-charset="UTF-8" action="http://127.0.0.1/class_year/update_classes/14/3" method="POST">
        <input type="hidden" value="DoXFDnPf1XrqEWRV8fIpRntOyJgr4go1lo760cAV" name="_token">
        <table class="data_table table_style_1">
            <tbody>
                <tr>
                    <td class="classes_name">
                    <input type="text" value="106" name="classes_name" size="5" required="required">
                    </td>
                    <td class="classes_command">
                    <input type="submit" value="更新">
                    <a class="delete_link" href="http://127.0.0.1/class_year/delete_classes/14/3">刪除</a></td>
                </tr>
            </tbody>
        </table>
    </form>
    <form accept-charset="UTF-8" action="http://127.0.0.1/class_year/update_classes/15/3" method="POST">
        <input type="hidden" value="DoXFDnPf1XrqEWRV8fIpRntOyJgr4go1lo760cAV" name="_token">
        <table class="data_table table_style_1">
            <tbody>
                <tr>
                    <td class="classes_name">
                    <input type="text" value="107" name="classes_name" size="5" required="required">
                    </td>
                    <td class="classes_command">
                    <input type="submit" value="更新">
                    <a class="delete_link" href="http://127.0.0.1/class_year/delete_classes/15/3">刪除</a></td>
                </tr>
            </tbody>
        </table>
    </form>
    <form accept-charset="UTF-8" action="http://127.0.0.1/class_year/update_classes/16/3" method="POST">
        <input type="hidden" value="DoXFDnPf1XrqEWRV8fIpRntOyJgr4go1lo760cAV" name="_token">
        <table class="data_table table_style_1">
            <tbody>
                <tr>
                    <td class="classes_name">
                    <input type="text" value="108" name="classes_name" size="5" required="required">
                    </td>
                    <td class="classes_command">
                    <input type="submit" value="更新">
                    <a class="delete_link" href="http://127.0.0.1/class_year/delete_classes/16/3">刪除</a></td>
                </tr>
            </tbody>
        </table>
    </form>
    <form accept-charset="UTF-8" action="http://127.0.0.1/class_year/update_classes/17/3" method="POST">
        <input type="hidden" value="DoXFDnPf1XrqEWRV8fIpRntOyJgr4go1lo760cAV" name="_token">
        <table class="data_table table_style_1">
            <tbody>
                <tr>
                    <td class="classes_name">
                    <input type="text" value="109" name="classes_name" size="5" required="required">
                    </td>
                    <td class="classes_command">
                    <input type="submit" value="更新">
                    <a class="delete_link" href="http://127.0.0.1/class_year/delete_classes/17/3">刪除</a></td>
                </tr>
            </tbody>
        </table>
    </form>
    <form accept-charset="UTF-8" action="http://127.0.0.1/class_year/update_classes/18/3" method="POST">
        <input type="hidden" value="DoXFDnPf1XrqEWRV8fIpRntOyJgr4go1lo760cAV" name="_token">
        <table class="data_table table_style_1">
            <tbody>
                <tr>
                    <td class="classes_name">
                    <input type="text" value="110" name="classes_name" size="5" required="required">
                    </td>
                    <td class="classes_command">
                    <input type="submit" value="更新">
                    <a class="delete_link" href="http://127.0.0.1/class_year/delete_classes/18/3">刪除</a></td>
                </tr>
            </tbody>
        </table>
    </form>
    <form accept-charset="UTF-8" action="http://127.0.0.1/class_year/update_classes/19/3" method="POST">        
        <table class="data_table table_style_1">
            <tbody>
                <tr>
                    <td class="classes_name">
                    <input type="text" value="111" name="classes_name" size="5" required="required">
                    </td>
                    <td class="classes_command">
                    <input type="submit" value="更新">
                    <a class="delete_link" href="http://127.0.0.1/class_year/delete_classes/19/3">刪除</a></td>
                </tr>
            </tbody>
        </table>
    </form>
</div>

<div id="year_course_time_selector">
    <table>
        <tbody>
            <tr id="day_row">
                <th id="year_course_time_description">101<br>〈教師F〉</th>
                <th>週一</th>
                <th>週二</th>
                <th>週三</th>
                <th>週四</th>
                <th>週五</th>
            </tr>
            <tr>
                <td class="course_column">第一節</td>
                <td data-selected="1" id="course_1" class="course" style="cursor: auto;"></td>
                <td data-selected="1" id="course_8" class="course" style="cursor: auto;">生活<br>〈教師A〉</td>
                <td data-selected="1" id="course_15" class="course"></td>
                <td data-selected="1" id="course_22" class="course" style="cursor: auto;"></td>
                <td data-selected="1" id="course_29" class="course"></td>
            </tr>
            <tr>
                <td class="course_column">第二節</td>
                <td data-selected="1" id="course_2" class="course"></td>
                <td data-selected="1" id="course_9" class="course">體育<br>〈教師B〉</td>
                <td data-selected="1" id="course_16" class="course"></td>
                <td data-selected="1" id="course_23" class="course">生活<br>〈教師A〉</td>
                <td data-selected="1" id="course_30" class="course"></td>
            </tr>
            <tr>
                <td class="course_column">第三節</td>
                <td data-selected="1" id="course_3" class="course">英語<br>〈教師D〉</td>
                <td data-selected="1" id="course_10" class="course"></td>
                <td data-selected="1" id="course_17" class="course">鄉土語<br>〈教師E〉</td>
                <td data-selected="1" id="course_24" class="course"></td>
                <td data-selected="1" id="course_31" class="course"></td>
            </tr>
            <tr>
                <td class="course_column">第四節</td>
                <td data-selected="1" id="course_4" class="course">健康<br>〈教師C〉</td>
                <td data-selected="1" id="course_11" class="course"></td>
                <td data-selected="1" id="course_18" class="course">生活<br>〈教師A〉</td>
                <td data-selected="1" id="course_25" class="course"></td>
                <td data-selected="1" id="course_32" class="course">體育<br>〈教師B〉</td>
            </tr>
            <tr>
                <td colspan="6" id="noon_break">午休</td>
            </tr>
            <tr>
                <td class="course_column">第五節</td>
                <td data-selected="0" id="course_5" class="course"></td>
                <td data-selected="1" id="course_12" class="course"></td>
                <td data-selected="0" id="course_19" class="course"></td>
                <td data-selected="0" id="course_26" class="course"></td>
                <td data-selected="0" id="course_33" class="course"></td>
            </tr>
            <tr>
                <td class="course_column">第六節</td>
                <td data-selected="0" id="course_6" class="course"></td>
                <td data-selected="1" id="course_13" class="course">英語<br>〈教師D〉</td>
                <td data-selected="0" id="course_20" class="course"></td>
                <td data-selected="0" id="course_27" class="course"></td>
                <td data-selected="0" id="course_34" class="course"></td>
            </tr>
            <tr>
                <td class="course_column">第七節</td>
                <td data-selected="0" id="course_7" class="course"></td>
                <td data-selected="1" id="course_14" class="course"></td>
                <td data-selected="0" id="course_21" class="course"></td>
                <td data-selected="0" id="course_28" class="course"></td>
                <td data-selected="0" id="course_35" class="course"></td>
            </tr>
        </tbody>
    </table>
</div>
@stop
