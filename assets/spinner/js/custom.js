/*globals $*/


var opts = {
		lines: 13, // The number of lines to draw
		length: 11, // The length of each line
		width: 5, // The line thickness
		radius: 17, // The radius of the inner circle
		corners: 1, // Corner roundness (0..1)
		rotate: 0, // The rotation offset
		color: '#FFF', // #rgb or #rrggbb
		speed: 1, // Rounds per second
		trail: 60, // Afterglow percentage
		shadow: false, // Whether to render a shadow
		hwaccel: false, // Whether to use hardware acceleration
		className: 'spinner', // The CSS class to assign to the spinner
		zIndex: 2e9, // The z-index (defaults to 2000000000)
		top: 'auto', // Top position relative to parent in px
		left: 'auto' // Left position relative to parent in px
	};

var target = document.createElement("div");
target.style.display = "none";
document.body.appendChild(target);
var spinner = new Spinner(opts).spin(target);
var overlay;
var spinnerActive=false;
	
function showLoader(){
	
	overlay = iosOverlay({
		text: "Loading",
		spinner: spinner
	});
	spinnerActive=true;
	return false;
}

function hideLoader(){
	if (spinnerActive) {
		window.setTimeout(function() {
			overlay.update({
				icon: "assets/spinner/img/check.png",
				text: "Success"
			});
		}, 3e3);

		window.setTimeout(function() {
			overlay.hide();
		}, 5e3);
		spinnerActive=false;
	}
	return false;
	
}
