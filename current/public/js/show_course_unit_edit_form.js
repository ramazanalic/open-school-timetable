/**
 * 顯示排課單元編輯表單
 */
$("#course_unit_list .edit_link").click(function() {
	dataRow = $(this).parentsUntil(".data_row").parent();
	formRow = dataRow.next();
	dataRow.hide();
	formRow.show();
	formRow.find("[disabled='disabled']").prop("disabled", false);
});

/**
 * 顯示限制課程時間選單
 */
$("#limit_course_time").click(function() {
	$("#course_time_selector").toggle($("#limit_course_time").prop("checked"));
});

