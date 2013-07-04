$(function() {
	// ADD FAVORITE
	$('.movie-feature li.add-favorite i').click(function(e){
		var clicked = $(this)
		,	movieID = clicked.data("feature")
		clicked.toggleClass("check")
		if(clicked.hasClass("check")){
			to_favorite = 1
		}else{
			to_favorite = 0

		}
		$.ajax({
			type: "GET",
			data: {movie_id: movieID, favorite: to_favorite},
			url: "/fulltube/windows/account/process/movie-feature.php"
		}).done(function(output){
			if(location.hash.toLowerCase().indexOf("favorite") != -1){
				var to_remove = $("#movie-list #"+movieID).parent()
				,	make_active = to_remove.prev()
				if(make_active.length == 0){
					make_active = to_remove.next()
				}
				if(make_active.length == 0){
					location.reload()
				}else{
					make_active.trigger("click")
					to_remove.remove()
				}
			}
		})
	})
	//PERSONAL RATING
	$('.movie-feature li.personal-rating i').click(function(){
		var clicked = $(this)
		,	movieID = clicked.data("feature")
		,	ratingIsset = clicked.find("span")
		var rating = parseInt(clicked.find("span").html())+1
		if(rating>5){
			rating = 0
			clicked.removeClass('check')
		}else if(rating==1){
			clicked.addClass('check')
		}
		setTimeout(function(){
			if(parseInt(clicked.find("span").html()) == rating){
				$.ajax({
					type: "GET",
					data: {movie_id: movieID, personal_rating: rating},
					url: "/fulltube/windows/account/process/movie-feature.php"
				}).done(function(output){
					//nothing to do ??....
				})
			}else{
				//do nothing
			}
		},2000)
		clicked.find("span").html(rating)
	})
})
