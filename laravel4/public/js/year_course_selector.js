/**
 * 課程點選效果、更新課程數值
 */
function updateCourseTime() {
    var courseTime = "";
    for ( i = 1; i < 36; i++) {
        if ($("#course_" + i).attr("data-selected") == "1") {
            courseTime += "1";
        } else {
            courseTime += "0";
        }
    }
    $("input[name='course_time']").val(courseTime);
}

/**
 * 先設定預設值
 */
$(document).ready(function() {
    updateCourseTime();

});

/**
 * 點選後變更數值
 */
$(".course").click(function() {
    if ($(this).attr("data-selected") == 0) {
        $(this).attr("data-selected", 1);
    } else {
        $(this).attr("data-selected", 0);
    }

    // 更新input數值
    updateCourseTime();
}).hover(function() {
    $(this).css('cursor', 'pointer');
}, function() {
    $(this).css('cursor', 'auto');
});