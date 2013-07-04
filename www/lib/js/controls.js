$(document).ready(function(){
	$('#movie-list li:first-child').addClass('active')
	$('#movies article:first-child').addClass('active')
	var movieList = $("#movie-list")
	,	navUl = $("#movie-list ul")
	,	navLi = $("#movie-list li")
	,	activeLi = $("#movie-list .active")
	,	navLiPos = 0
	,	activeLiPos = 0
	,	navUlPos = 0
	,	movieListViewScrolls = 0
	,	$window = $(window)
	,	activeInfo = $("#movies article.active")
	,	windowPop = $("#window-pop")
	activeLi.focus()
	//keyControl
	$window.keydown(function(e){
		//left
		if(e.keyCode == 37){
			return false;
		}
		//right
		if(e.keyCode == 39){ 
			return false;
		}
		//up
		if(e.keyCode == 38){
			navUpDown('up')
		}
		if(e.keyCode == 40){
			navUpDown('down')
		}
		//enter
		if(e.keyCode == 13){
			if(!(windowPop.hasClass("active"))){
				activeInfo.find(".play-movie").trigger('click')
			}
		}
	})
	//scrollControl
	$window.mousewheel(function(e, delta) {
		if(delta > 0){
			navUpDown('up')
		}
		if(delta < 0){
			navUpDown('down')
		}
	})
	//clicks
	navLi.click(function(e){ 
		var that = $(this)
		if(that.hasClass("active")==false){
			$(this).addClass("active")
			setActive()
		}
		e.preventDefault()
	})
	function navUpDown(direction){
		if(!(windowPop.hasClass("active"))){
			if(direction=='up' || direction == 'down'){
				//up
				if(direction == 'up'){
					var prevLi = activeLi.prev()
					if(prevLi.length != false){
						prevLi.trigger('click')
					}else{
						var lastLiOffset = navLi.last().offset().top
						navLi.last().trigger('click')
						if(lastLiOffset>navUl.height()){
							movieListViewScrolls = Math.floor(lastLiOffset/navUl.height())-1
						}
					}
				}
				//down
				if(direction == 'down'){
					var nextLi = activeLi.next()
					if(nextLi.length != false){
						//nextLi.addClass("active")
						nextLi.trigger('click')
					}else{
						navLi.first().trigger('click')
						movieListViewScrolls = 1
					}
				}
				//setActive()
				movieListView()
			}
		}
	}
	function movieListView(){
		activeLiPos = activeLi.offset().top+navLi.height()
		if(activeLiPos-10>movieList.height()){
				movieListViewScrolls = movieListViewScrolls+1
				navUl.css("top", -(movieListViewScrolls*100)+"%")
				//navUl.stop('true','true').animate({top: -(movieListViewScrolls*100)+"%"},'fast')
		}
		if(activeLiPos<10){
				movieListViewScrolls = movieListViewScrolls-1
				navUl.css("top", -(movieListViewScrolls*100)+"%")
				//navUl.stop('true','false').animate({top: -(movieListViewScrolls*100)+"%" },'fast')
		}
	}
	function setActive(){
		activeLi.removeClass("active")
		activeInfo.removeClass("active")
		activeLi = $("#movie-list .active")
		activeInfo = $("#movies article[data-movie-id='"+activeLi.find('a').attr("id")+"']")
		activeInfo.addClass("active")
	}
})
