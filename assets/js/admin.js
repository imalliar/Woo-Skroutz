jQuery(document).ready(function($) {
    // Remove the "successful" message after 3 seconds
    if (".updated") {
        setTimeout(function() {
            $(".updated").fadeOut();
        }, 10000);
    } 
    
    $('#country').on('changed.bs.select', function() {
    	var value = $('#country').selectpicker('val');
		
    });
});    