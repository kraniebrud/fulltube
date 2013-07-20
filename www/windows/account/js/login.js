$(function() {
	var	windowState = $("#window-state")
	,	windowMessage = $("#window-message")
//submit login
	$("#login-form").submit(function(e){
		e.preventDefault()
		var submit_username = $("#username").val(),
		submit_password = $("#password").val()
		$.ajax({
			type: "POST",
			url: "/fulltube/www/windows/account/process/login.php",
			data: {username: submit_username, password: submit_password}
		}).done(function(output){
			if(output != "success"){
				windowState.find("header").append(output)
			}else{
				$.ajax({
					type: "GET",
					url: "/fulltube/www/windows/account/account.php"
				}).done(function(output){
					window.location.href = '/fulltube'
				})
			}
		})
	})
//register user
	$("#register-user").click(function(e){
		e.preventDefault()
		$.ajax({
			type: "GET",
			url: "/fulltube/www/windows/account/register.php"
		}).done(function(output){
			windowState.html(output)
		})
	})
})
