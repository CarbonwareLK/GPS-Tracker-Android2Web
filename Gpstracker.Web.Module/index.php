<!DOCTYPE html>
<html lang="en">

	<?php
	session_start();

	$logged = $_SESSION['logged'];

	if (!$logged) {
		header("location:/login.html");
	}
	?>

	<head>
		<meta charset="utf-8" />

		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
		Remove this if you use the .htaccess -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

		<title>index</title>
		<meta name="description" content="" />
		<meta name="author" content="dew" />

		<meta name="viewport" content="width=device-width; initial-scale=1.0" />

		<!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
		<link rel="shortcut icon" href="/favicon.ico" />
		<link rel="apple-touch-icon" href="/apple-touch-icon.png" />

		<!-- Bootstrap -->
		<link href="bootstrap/css/bootstrap.css" rel="stylesheet" id="main-theme-script">
		<link href="css/themes/default.css" rel="stylesheet" id="theme-specific-script">
		<link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

		<!--		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script> -->

		<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js"></script>
		<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>

		<script type="text/javascript" charset="utf-8">
			function createMarker(name, latlng) {
				var marker = new google.maps.Marker({
					position : latlng,
					map : map
				});
				google.maps.event.addListener(marker, "click", function() {
					if (infowindow)
						infowindow.close();
					infowindow = new google.maps.InfoWindow({
						content : name
					});
					infowindow.open(map, marker);
				});
				return marker;
			}

			function initialize() {

				var locations = new Array();
				var map;
				$.ajax({//this is the php file that processes the data and send mail
					url : "./phpscript/index.php",

					//GET method is used
					type : "GET",

					success : function(data) {

						var strArray = data.toString().split('/');

						for (var i = 0; i < strArray.length; i++) {
							var val = (strArray[i]);
							var subAr = val.split(',')
							locations[i] = [subAr[0], parseFloat(subAr[1]), parseFloat(subAr[2]), parseFloat(subAr[3])];
						};

						var flightPlanCoordinates = [];

						for (var i = 0; i < locations.length; i++) {
							var lat = locations[i][1];
							var lang = locations[i][2];

							if (!isNaN(lat) || !isNaN(lang)) {
								var loca = new google.maps.LatLng(lat, lang);
								flightPlanCoordinates.push(loca);

								console.log(lat + "  " + lang);
							}

							// [new google.maps.LatLng(37.772323, -122.214897), new google.maps.LatLng(21.291982, -157.821856), new google.maps.LatLng(-18.142599, 178.431), new google.maps.LatLng(-27.46758, 153.027892)];
						};

						var myLatLng = flightPlanCoordinates[0];
						var mapOptions = {
							zoom : 7,
							center : myLatLng,
							mapTypeControl : true,
							mapTypeControlOptions : {
								style : google.maps.MapTypeControlStyle.DROPDOWN_MENU
							},
							zoomControl : true,
							zoomControlOptions : {
								style : google.maps.ZoomControlStyle.SMALL
							},
							mapTypeId : google.maps.MapTypeId.ROADMAP
						};
						map = new google.maps.Map(document.getElementById('map'), mapOptions);
						var loca = new google.maps.LatLng(locations[0][1], locations[0][2]);

						console.log(loca);
						console.log(flightPlanCoordinates);

						var flightPath = new google.maps.Polyline({
							path : flightPlanCoordinates,
							strokeColor : '#FF0000',
							strokeOpacity : 1.0,
							strokeWeight : 2
						});

						flightPath.setMap(map);
						var infowindow = new google.maps.InfoWindow();
						for ( i = 0; i < locations.length; i++) {

							marker = new google.maps.Marker({
								position : new google.maps.LatLng(locations[i][1], locations[i][2]),
								map : map
							});

							google.maps.event.addListener(marker, 'click', (function(marker, i) {

								return function() {
									infowindow.setContent(locations[i][0]);
									infowindow.open(map, marker);

								}
							})(marker, i));
						}

					}
				});

				$(function() {
					$("#searchbox").autocomplete({

						source : function(request, response) {

							if (geocoder == null) {
								geocoder = new google.maps.Geocoder();
							}
							geocoder.geocode({
								'address' : request.term
							}, function(results, status) {
								if (status == google.maps.GeocoderStatus.OK) {

									var searchLoc = results[0].geometry.location;
									var lat = results[0].geometry.location.lat();
									var lng = results[0].geometry.location.lng();
									var latlng = new google.maps.LatLng(lat, lng);
									var bounds = results[0].geometry.bounds;

									geocoder.geocode({
										'latLng' : latlng
									}, function(results1, status1) {
										if (status1 == google.maps.GeocoderStatus.OK) {
											if (results1[1]) {
												response($.map(results1, function(loc) {
													return {
														label : loc.formatted_address,
														value : loc.formatted_address,
														bounds : loc.geometry.bounds
													}
												}));
											}
										}
									});
								}
							});
						},
						select : function(event, ui) {
							var pos = ui.item.position;
							var lct = ui.item.locType;
							var bounds = ui.item.bounds;

							if (bounds) {
								map.fitBounds(bounds);
							}
						}
					});

				});

			}
		</script>

	</head>

	<body   onload="initialize()">
		<!-- Top navigation bar -->
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container-fluid">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a>
					<a class="brand" href="index.html">GPS Vehicle Location Tracker</a>

					<div class="pull-right">
						<a class="brand"  style="font-size: 15px;" >Welcome

						<?php

						session_start();

						$username = $_SESSION['fname'];
						
						echo $username;
						?> </a>
						<a class="brand"  style="color: red"  href="./phpscript/loggout.php">logout</a><span class="divider"></span>
					</div>
				</div>
			</div>
		</div>

		<div  style="height: 500px;margin: 0px;padding: 0px;width: 100%">
			<div style="width: 70%;height: 500px;float: left;padding: 10px;">
				<div  id="map" style="width:auto; height: 100%;">

				</div>

			</div>

			<div style="width: 25%;float: left;padding: 10px;">
				<input type="text" value="" id="searchbox" placeholder="Search locations" style=" width:100%;height:30px; ">
			</div>
		</div>

	</body>
</html>
