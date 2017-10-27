jQuery(document).ready(function($) {
    // Remove the "successful" message after 3 seconds
    if (".updated") {
        setTimeout(function() {
            $(".updated").fadeOut();
        }, 10000);
    } 
    
    $('#country').on('changed.bs.select', function() {
    	var value = $('#country').selectpicker('val');
		$.get(ajaxurl, { 'action' : 'get_states', 'current_country' : value }, function(data) {
			$('#country').html('');
			if(data=='false') return;
			$.each(data, function(i, item) {
				$('#country').append($('<option>', {
					value: item.value,
					text: item.text
				}));
			});
		});
    });
});    