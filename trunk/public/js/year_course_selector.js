/**
 * 課程點選效果、更新課程數值
 */
$(".course").click(function() {
    if ($(this).attr("data-selected") == 0) {
        $(this).attr("data-selected", 1);
    } else {
        $(this).attr("data-selected", 0);
    }

    // 更新input數值
    var courseTime = "";
    for ( i = 1; i < 36; i++) {
        courseTime += $("#course_" + i).attr("data-selected");
    }

    $("input[name='course_time']").val(courseTime);
}).hover(function() {
    $(this).css('cursor', 'pointer');
}, function() {
    $(this).css('cursor', 'auto');
});