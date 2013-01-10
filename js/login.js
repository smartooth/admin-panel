$(document).ready(function() {
	$("form").submit(function(event) {
		var userid = $("input[name=userid]").val();
		var passwd = $("input[name=passwd]").val();

		if (userid.length == 0 || passwd.length == 0) {
			alert('Please complete the form before continuing.');
			event.preventDefault();
		}
	});
});