/**
 * 新增排課設定，顯示限制課程時間選單
 */
$("#course_time_selector").toggle($("#limit_course_time").prop("checked"));
$("#limit_course_time").click(function() {
	$("#course_time_selector").toggle($("#limit_course_time").prop("checked"));
});

$(".edit_link").click(function() {
	$.ajax({
		url : $(this).attr("href"),
		cache : false,
		type : 'GET'
	}).done(function(html) {
		$("#course_unit_form").html(html);
	});

	return false;
});
