jQuery(document).ready(function($) {
    $('.bootswitch').bootstrapSwitch();
    
    
	
	// Remove the "successful" message after 3 seconds
    if (".updated") {
        setTimeout(function() {
            $(".updated").fadeOut();
        }, 10000);
    } 
    
    $('#country').on('changed.bs.select', function() {
    	var value = $('#country').selectpicker('val');
		$.get(ajaxurl, { 'action' : 'get_states', 'current_country' : value }, function(data) {
			$('#state').find('option').not(':first').remove();
			 $('#state').selectpicker('refresh');
			if(data=='false') return;
			data = JSON.parse(data);
			$.each(data, function(key, value) {
				$('#state').append($('<option>', {
					value: key,
					text: value
				}));
			});
			$('#state').selectpicker('refresh');
			
		});
    });
});    