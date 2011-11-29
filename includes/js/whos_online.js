function refreshOnlineList() {
	$.ajax({
		type: "GET",
		url: "ajax_provider.php",
		data: "component=whos_online",
		cache: false,
		success: function(html) {
			$("#online_container").fadeIn(400).empty().append(html);
		}		
	});	
}