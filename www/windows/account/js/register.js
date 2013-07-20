$(function() {
	var	windowState = $("#window-state")
	,	windowMessage = $("#window-message")
//submit register
	$("#register-form").submit(function(e){
		e.preventDefault();
		var submit_username = $("#username").val(),
		submit_password = $("#password").val()
		submit_email = $("#email").val()
		$.ajax({
			type: "POST",
			url: "/fulltube/www/windows/account/process/register.php",
			data: {username: submit_username, password: submit_password, email: submit_email}
		}).done(function(output){
			if(output != "success"){
				windowMessage.addClass("error").html(output)
			}else{
				output = "U R NOW SUCCESFULLFY REGISTREDERERD"
				windowState.html(output)
			}
		})
	})
})
//register user 
/*
	$("#register-user").click(function(e){
		e.preventDefault()
		$.ajax({
			type: "GET",
			url: "/fulltube/windows/account/register.html"
		}).done(function(output){
			windowState.html(output)
		})
	})
})
* */
