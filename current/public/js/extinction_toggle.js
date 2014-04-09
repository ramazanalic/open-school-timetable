function toggleExtinction() {
	if ($("#extinction").prop("checked") == true) {
		$("#extinction_time_label").css("display", "inline-block");
	} else {
		$("#extinction_time_label").css("display", "none");
	}
}


$(document).ready(toggleExtinction);
$("#extinction").click(toggleExtinction);

