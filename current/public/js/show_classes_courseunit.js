/**
 * 顯示班級排課資料
 */
function showClassesCourseunit(classes_id) {
	$(".classes_courseunit").hide();
	$("#classes_courseunit_" + classes_id).show();

}

// 顯示班級排課資料
$(".showClassesCourseunit").click(function() {
	showClassesCourseunit($(location).attr('hash').substring(1));
});

// 預設顯示班級排課資料
$(document).ready(function() {
	showClassesCourseunit($(location).attr('hash').substring(1));
});

