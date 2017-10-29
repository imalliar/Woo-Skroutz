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
			$('#state').html('');
			 $('#state').selectpicker('refresh');
			if(data=='false') return;
			if($.type(data)=="string") {
				data = JSON.parse(data);
			}
			$.each(data, function(i, item) {
				$('#state').append($('<option>', {
					value: item.value,
					text: item.text
				}));
				$('#state').selectpicker('refresh');
			});
		});
    });
});    