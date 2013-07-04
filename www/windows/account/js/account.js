$(function() {
	var	windowState = $("#window-state")
	,	windowPop = $("#windowPop")
	,	accountContent = $("#account-content")
//logout
	$("#logout-user").click(function(e){
		$.ajax({
			type: "GET",
			url: "/fulltube/windows/account/process/logout.php"
		}).done(function(output){
			location.reload()
		})
	})
})
