/**
 * 顯示排課單元表單 
 */
$(".showCourseUnitForm").click(function() {	
	$.ajax({
		url : "/timetable/get_course_unit_form/" + $(this).attr("data-teacher_id"),
		cache : false,
		type : 'GET'
	}).done(function(html) {
		$("#course_unit_form").html(html);
	});
});
