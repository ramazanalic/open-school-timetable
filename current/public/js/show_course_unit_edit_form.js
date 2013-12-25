/**
 * 編輯排課設定，顯示限制課程選單
 */
function showEditLimitCourseTime() {
	$("#course_time_selector").toggle($(".edit_limit_course_time").prop("checked"));
	$(".edit_limit_course_time").click(function() {
		$("#course_time_selector").toggle($(".edit_limit_course_time").prop("checked"));
	});

}

// 更新排課時間限制表格
function updateCourseTimeByValue(courseTime) {
	for ( i = 1; i < 36; i++) {
		$("#course_" + i).attr("data-selected", courseTime.substring(i, i - 1));
		if (courseTime.substring(i, i - 1) == "1") {
			$("#course_" + i).html("<img alt=\"check\" src=\"/image/check.png\">");
		}
	}
}

/**
 * 顯示排課單元編輯表單
 */
$("#course_unit_list .edit_link").click(function() {
	dataRow = $(this).parentsUntil(".data_row").parent();
	formRow = dataRow.next();
	dataRow.hide();
	formRow.show();
	formRow.find("[disabled='disabled']").prop("disabled", false);
	showEditLimitCourseTime();
	updateCourseTimeByValue(formRow.find("input[type='hidden']").val());
});

/**
 * 新增排課設定，顯示限制課程時間選單
 */
$("#limit_course_time").click(function() {
	$("#course_time_selector").toggle($("#limit_course_time").prop("checked"));
});

