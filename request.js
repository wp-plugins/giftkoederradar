if (GBrowserIsCompatible()) {
	var GoogleMap 	= new GMap2(document.getElementById("gkradar-map"));
	var Bounds 		= new GLatLngBounds();
	GoogleMap.setCenter(new GLatLng(47.5358719, 10.6715888), 13);
	GoogleMap.setUIToDefault();
	$.get("http://www.giftkoeder-radar.com/api/1.0/location", { apiKey: GkRadarApiKey }, function(data) {
		var icon = new GIcon();
		icon.iconSize = new GSize(32, 37);
		icon.shadowSize = new GSize(32, 37);
		icon.iconAnchor = new GPoint(10, 10);
		icon.infoWindowAnchor = new GPoint(10, 10);
		$.each(data, function(index) {
            if (data[index].status == 'HUNCH') {
				icon.image = "http://www.giftkoeder-radar.com/images/mapsMarkerHunch.png";
				icon.shadow = "http://www.giftkoeder-radar.com/images/mapsMarkerHunch.png";
			}
			if (data[index].status == 'OFFICIAL') {
				icon.image = "http://www.giftkoeder-radar.com/images/mapsMarkerOfficial.png";
				icon.shadow = "http://www.giftkoeder-radar.com/images/mapsMarkerOfficial.png";
			}
			if (data[index].status == 'APPROVED' || data[index].status == 'CONFIRMED') {
				icon.image = "http://www.giftkoeder-radar.com/images/mapsMarker.png";
				icon.shadow = "http://www.giftkoeder-radar.com/images/mapsMarker.png";
			}
			var point = new GLatLng(parseFloat(data[index].latitude), parseFloat(data[index].longitude));
			var marker = new GMarker(point, icon);
			var html = '<b>' + data[index].dateCreated + ' - ' + data[index].title + '</b>';
			if (data[index].street.length > 0) { 
				html = html + '<br />' + data[index].street;
			}
			if (data[index].zipcode.length > 0) {
				html = html + '<br />' + data[index].zipcode + ' ' + data[index].city;
			}
			html = html + '<br /><br /><b>Mit freundlicher Unterstützung von:</b><br /><a href="http://www.giftkoeder-radar.com">www.giftkoeder-radar.com</a>';
			GEvent.addListener(marker, "click", function() {
        		marker.openInfoWindowHtml(html);
        	});
			GoogleMap.addOverlay(marker);
   			Bounds.extend(point);
        });
    	GoogleMap.setZoom(GoogleMap.getBoundsZoomLevel(Bounds));
		GoogleMap.setCenter(Bounds.getCenter());
	}, "json");
}