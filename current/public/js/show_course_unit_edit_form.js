/**
 * 顯示排課設定
 */
$("#course_time_selector").toggle($("#limit_course_time").prop("checked"));
$("#limit_course_time").click(function() {
	$("#course_time_selector").toggle($("#limit_course_time").prop("checked"));
});

/**
 * 顯示編輯排課設定選單
 */
$(".showCourseUnitEditForm ").click(function() {
	$.ajax({
		url : $(this).attr("href"),
		cache : false,
		type : 'GET'
	}).done(function(html) {
		$("#course_unit_form").html(html);
	});

	return false;
});
