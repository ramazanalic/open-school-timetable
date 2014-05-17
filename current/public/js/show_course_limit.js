/**
 * 顯示排課限制選擇區
 */
$("#course_time_selector").toggle($("#limit_course_time").prop("checked"));
$("#limit_course_time").click(function() {
	$("#course_time_selector").toggle($("#limit_course_time").prop("checked"));
}); 