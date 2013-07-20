$(function() {
	var windowPop = $("#window-pop")
	,	windowPopInner = $("#window-pop article")
	windowPop.click(function(e){
		if($(e.target).is(windowPop)){
			windowPop.removeClass('active')
			windowPopInner.html("")
		}
	})
	$(window).keydown(function(e){
		if(e.keyCode == 27){
			$("#window-pop").removeClass("active")
			windowPopInner.html("")
		}
	})
	/*YOUTUBE*/
	$(".play-tube").click(function(e){
		e.preventDefault()
		var youtubeUrl = $(this).attr('href')
		$.ajax({
			type: "GET",
			url: "/fulltube/www/windows/player/youtube.php",
			data: {video: youtubeUrl }
		}).done(function(output){
			windowPop.addClass('active')
			windowPopInner.html(output).width('600px')
			$("#video-player").focus()
		})
	})
	/*POSTER*/
	$("#movies figure a").click(function(e){
		e.preventDefault()
		var that = $(this)
		,	windowWidth = that.width()
		,	imagePopped
		windowPopInner.html('<img src="'+that.attr('href')+'">')
		imagePopped = windowPopInner.find('img')
		imagePopped.load(function(){
			windowPop.addClass('active')
			windowPopInner.width(imagePopped.width())
			imagePopped.width(imagePopped.width()).height(imagePopped.height())
		})
	})
	/*ADD MOVIE*/
	$("#add-movie").click(function(e){
		e.preventDefault()
		popWindow("/fulltube/www/windows/movie/add-movie.php", "600px")
	})
	/*REORDER*/
	$("#reorder, #movie-order a.change-order").click(function(e){
		e.preventDefault()
		popWindow("/fulltube/www/windows/movie/reorder.html")
	})
	/*SEARCH*/
	$("#search").click(function(e){
		e.preventDefault()
		popWindow("/fulltube/www/windows/movie/search.html")
	})
	/*account*/
	$("#account").click(function(e){
		e.preventDefault()
		popWindow("/fulltube/www/windows/account/account.php")
	})
	function popWindow(popThisUrl, windowSize){
		$.ajax({
			type: "GET",
			url: popThisUrl
		}).done(function(output){
			windowPop.addClass('active')
			windowPopInner.html(output).width(windowSize)
			windowPopInner.find("input:first").focus()
		})
	}
})
