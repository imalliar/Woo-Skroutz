
jQuery(document).ready(function($) {
    $('.bootswitch').bootstrapSwitch();
           
    $('.bootswitch').on('switchChange.bootstrapSwitch', function(event, state) {
    	initShippingControls(state);
    });    
    
    initShippingControls($('.bootswitch').bootstrapSwitch('state'));
	
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

function initShippingControls(state) {
	if(state) {
		jQuery('#zip').prop('disabled', true);
		
		jQuery('#state').prop('disabled', true);
		jQuery('#state').selectpicker('refresh');
		
		jQuery('#country').prop('disabled', true);
		jQuery('#country').selectpicker('refresh');
	} else {
		jQuery('#zip').prop('disabled', false);    		
		
		jQuery('#state').prop('disabled', false);
		jQuery('#state').selectpicker('refresh');		
		
		jQuery('#country').prop('disabled', false);
		jQuery('#country').selectpicker('refresh');
	}	
}
