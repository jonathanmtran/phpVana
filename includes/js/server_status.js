function get_characters(world, channel) {
	$.ajax({
		type: "GET",
		url: "ajax_provider.php",
		data: "component=server_status&wid=" + world + "&cid=" + channel,
		cache: false,
		success: function(html) {
			$("#link-" + world + channel).remove();
			$("#online_characters-" + world + channel).append(html);
		}		
	});	
}

function isOnline(id) {
	$.ajax({
		type: "GET",
		url: "ajax_provider.php",
		data: "component=server_status_online&id=" + id,
		cache: false,
		success: function(html) {
			$("#online-" + id).empty().append(html);
		}		
	});	
}