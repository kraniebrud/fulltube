$(function() {
	var windowHash = getHash()
	$(window).on('hashchange', function() {
		windowHash = getHash()
		$("#window-pop").removeClass('active')
		movieOrders(windowHash[1])
	})
	movieOrders(windowHash[1])
})
function getHash(){
	var sorting = window.location.hash,
	url = sorting.split(':')
	return url;
}
function movieOrders(movieOrder){
	var	movieHolder = $("#movie-holder"),
	windowState = $("#windowState")
	windowState.append().html('<div id="load"><i class="icon-spinner icon-spin .icon-3x"></i></div>')
	$.ajax({
		type: "GET",
		url: "/fulltube/movielist.php",
		data: {movie: movieOrder}
	}).done(function(moviesReturned){
		movieHolder.html(moviesReturned)
		//$('#movie-holder').trigger('loadControlVars')
	})
}
