var mapStyle;

var data;

var bounds;

var target = "rhys";

function initMap()
{
	$.get('map-style.json').done(function(_mapStyles)
	{
		$.get('data/'+target+'/manifest.json').done(function(_manifest)
		{
			doMap(_mapStyles, _manifest);
		});
	});
}

function doMap(styles, manifest)
{
	var map = new google.maps.Map(
		document.getElementById('map'),
		{
			center: {lat: 39.98945705957293, lng: -4.110076961621083},
			scrollwheel: false,
			zoom: 8,
			styles: styles,
			disableDefaultUI: true,
			draggable: false,
			scrollwheel: false,
			scaleControl: true
		}
	);

	bounds = new google.maps.LatLngBounds();

	var defs = [];

	for(var i = 0; i < manifest.length; i++)
	{
		defs.push(addLine(manifest[i], map));
	}
	
	$.when.apply($, defs).then(function()
	{
		map.fitBounds(bounds);
	});
}

function getPolyLine(points)
{
	return new google.maps.Polyline({
		path: points,
		strokeColor: "#fc4c02",
		strokeOpacity: 1,
		strokeWeight: 10
	});
}

function addLine(file, map)
{
	var dfd = jQuery.Deferred();
	
	$.get(
		"data/" + target + "/" + file, 
		function(xml)
		{
			var points = [];

			var $xml = $(xml);
			
			$xml.find("trkpt").each(function()
			{
				var lat = $(this).attr("lat");

				var lon = $(this).attr("lon");

				var p = new google.maps.LatLng(lat, lon);

				points.push(p);

				bounds.extend(p);
			});

			var $endNode = $('trkseg > trkpt:last-child', $xml);

			var marker = new google.maps.Marker({
				position: new google.maps.LatLng($endNode.attr("lat"), $endNode.attr("lon")),
				map: map,
				title: $xml.find('name').text(),
				icon: {
					url: "lib/img/ticker.png",
					anchor: new google.maps.Point(3, 3),
				} 
			});

			var poly = getPolyLine(points);
			
			poly.setMap(map);

			dfd.resolve("done");
		},
		"xml"
	);

	return dfd.promise();
}