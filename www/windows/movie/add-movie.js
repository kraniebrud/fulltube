$(function() {
//input search
	var	windowState = $("#window-state")
	,	firstWindow = $("#window-state").html();
//submitting movie request
	$("#lookup-movie").click(function(e) {
		var movieTitle = $("#movie_title").val(),
		youtubeUrl = $("#youtube_url").val()
		videoQuality_id = $("#video_quality").val()
		windowState.html('<div id="load"><i class="icon-spinner icon-spin .icon-3x"></i> LOADING MOVIE TITLES...</div>')
		setTimeout(function(){
			$.ajax({
				type: "POST",
				url: "/fulltube/windows/movie/process/movie-fetcher.php",
				data: {search: movieTitle, youtube_url: youtubeUrl, quality_id: videoQuality_id}
//confirm movie request
			}).done(function(msg){
				windowState.html(msg)
				$("#store-movie").click(function(e){
					e.preventDefault()
					var movieToAdd = $('select[name="insert_movie"]').val()
					windowState.html('<div id="load"><i class="icon-spinner icon-spin .icon-3x"></i> STORING MOVIE DATA...</div>')
					setTimeout(function(){
						$.ajax({
							type: "POST",
							url: "/fulltube/windows/movie/process/store-movie.php",
							data: {href: movieToAdd}
						}).done(function(msg){
							window.location.href = '/fulltube'
							//windowState.html(msg)
						})
					},3000)
				})
				$("#return").click(function(e){
					e.preventDefault()
					$("#add-movie").trigger('click')
				})	
			})
		},3000)
	})
})
