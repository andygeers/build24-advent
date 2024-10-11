function zoomIn() {
	// console.log("Old zoom was " + document.body.style.zoom );
	// var currentZoom = document.body.style.zoom;
	// if (currentZoom == null) {
	// 	currentZoom = 1;
	// } else {
	// 	currentZoom = parseFloat(currentZoom);
	// }
	// document.body.style.zoom = currentZoom + 1.0;
	// console.log("New zoom is " + document.body.style.zoom );	
	$('#content').animate({ 'zoom': 20 }, 400);
}

function zoomOut() {
	$('#content').animate({ 'zoom': 1 }, 400);
}

function initialiseAdvent() {
	$('#zoom-in').click(function() { zoomIn(); });
	$('#zoom-out').click(function() { zoomOut(); });
	
	$('.transition-button').on('click', function() {
        var targetPage = $(this).data('target');
        var button = $(this);

        if (button.hasClass('day')) {
	        var buttonOffset = button.offset(); // Get button position relative to the document

	        var buttonWidth = button.outerWidth(); // Get button width
	        var buttonHeight = button.outerHeight(); // Get button height

	        // Calculate the center of the button
	        var buttonCenterX = buttonOffset.left + buttonWidth / 2;
	        var buttonCenterY = buttonOffset.top + buttonHeight / 2;

	        // Position the stencil overlay at the center of the button
	        // and activate the stencil overlay animation
	        $('.stencil-overlay').css({
	            'top': buttonCenterY + 'px',
	            'left': buttonCenterX + 'px'
	        });
	    }
	    
	    $('.stencil-overlay').addClass('active');	    

        // Wait for the animation to finish, then show the target page
        setTimeout(function() {
            $('.page-content').hide(); // Hide all pages
            $(targetPage).show();      // Show the target page
            $('.stencil-overlay').removeClass('active'); // Reset the overlay
        }, 1000); // Matches the duration of the CSS transition
    });
}