$(document).ready(function() {
	//setInterval(function() { myTimer() }, 1000);
});

function myTimer()  {
	//var currDate = new Date();
	//var d = new Date(currDate.getFullYear(), currDate.getMonth(), currDate.getDay(), currDate.getHours()+13);
	var d = new Date();
	document.getElementById("demo").innerHTML = $.format.date(d, "HH:mm:ss yyyy年MM月dd日");
	delete d;
}