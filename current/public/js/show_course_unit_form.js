/**
 * 顯示排課設定表單
 */
function showCourseUnitForm(teacher_id) {
	$.ajax({
		url : "/timetable/get_course_unit_form/" + teacher_id,
		cache : false,
		type : 'GET'
	}).done(function(html) {
		$("#course_unit_form").html(html);
	});

	// 點選效果
	$(".showCourseUnitForm").attr("data-selected", 0);
	$(".showCourseUnitForm[data-teacher_id='" + teacher_id + "']").attr("data-selected", 1);
}

// 點選後出現排課設定
$(".showCourseUnitForm").click(function() {
	showCourseUnitForm($(this).attr("data-teacher_id"));
});

// 預設出現排課設定
$(document).ready(function() {
	showCourseUnitForm($(location).attr('hash').substring(1));
});

