<!DOCTYPE html>
<html lang="en">

	<!-- Mirrored from wbpreview.com/previews/WB00958H8/index.html by HTTrack Website Copier/3.x [XR&CO'2008], Tue, 14 Aug 2012 15:00:16 GMT -->
	<head>

		<?php
		session_start();

		$logged = $_SESSION['logged'];

		if (!$logged) {
			header("location:/login.html");
		}
		?>

		<meta charset="utf-8">
		<title>Admin Simplenso - Dashboard</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="HTML5 Admin Simplenso Template">
		<meta name="author" content="ahoekie">

		<!-- Bootstrap -->
		<link href="bootstrap/css/bootstrap.css" rel="stylesheet" id="main-theme-script">
		<link href="css/themes/default.css" rel="stylesheet" id="theme-specific-script">
		<link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

		<!-- Full Calender -->
		<link rel="stylesheet" type="text/css" href="scripts/fullcalendar/fullcalendar/fullcalendar.css" />

		<!-- Bootstrap Date Picker -->
		<link href="scripts/datepicker/css/datepicker.css" rel="stylesheet">

		<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
		<link rel="stylesheet" href="scripts/blueimp-jQuery-File-Upload/css/jquery.fileupload-ui.css">

		<!-- Bootstrap Image Gallery styles -->
		<link rel="stylesheet" href="../../../blueimp.github.com/Bootstrap-Image-Gallery/css/bootstrap-image-gallery.min.css">

		<!-- Uniform -->
		<link rel="stylesheet" type="text/css" media="screen,projection" href="scripts/uniform/css/uniform.default.css" />

		<!-- Chosen multiselect -->
		<link type="text/css" href="scripts/chosen/chosen/chosen.intenso.css" rel="stylesheet" />

		<!-- Simplenso -->
		<link href="css/simplenso.css" rel="stylesheet">

		<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Le fav and touch icons -->
		<link rel="shortcut icon" href="images/ico/favicon.ico">
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.html">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.html">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.html">
		<link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.html">

		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
		<script type="text/javascript"
		src="http://maps.googleapis.com/maps/api/js?&sensor=true"></script>
		<?php
		session_start();
		$mysql_host = "mysql3.000webhost.com";
		$mysql_database = "a6878008_gps";
		$mysql_user = "a6878008_dewmal";
		$mysql_password = "dewmal91";

		$con = mysql_connect($mysql_host, $mysql_user, $mysql_password);

		if (!$con) {
			die('Could not connect: ' . mysql_error());
		}

		mysql_select_db($mysql_database, $con);
		$user_id = $_SESSION['user'];
		$sql = "SELECT * FROM location WHERE user_id=$user_id";

		$result = mysql_query($sql);
		// Mysql_num_row is counting table row
		$count = mysql_num_rows($result);

		// If result matched $myusername and $mypassword, table row must be 1 row
		if ($count != 0) {
			$i = 0;
			$num = mysql_numrows($result);

			$map_arr = array();

			$map = "[";

			while ($i < $num) {

				$f1 = mysql_result($result, $i, "atti");
				$f2 = mysql_result($result, $i, "lon");
				$f3 = mysql_result($result, $i, "time");
				$i++;
				$json_array = array();
				$json_array[0] = $f1;
				$json_array[1] = $f2;
				$json_array[2] = $f3;
				$json_array[3] = $i;

				$map_arr[$i] = $map_arr;

				$map .= "['$f3',$f1,$f2,$i],";

			}

			$map = substr_replace($map, "", -1);
			;
			$map .= "]";

			//$js_array = json_encode(mysql_fetch_array($result));

			//echo $js_array['atti'] . "";
			//Print_r($_SESSION);
			// Register $myusername, $mypassword and redirect to file "login_success.php"
			//session_register("email");
			//session_register("password");
			//header("location:/");

			echo "
<script type=\"text/javascript\" >
function initialize() {

var locations = $map;

var centerLat=locations[0][1];
var centerLng=locations[0][2];

var map = new google.maps.Map(document.getElementById('map'), {
zoom : 10,
center : new google.maps.LatLng(centerLat, centerLng),
mapTypeId : google.maps.MapTypeId.ROADMAP
});

var infowindow = new google.maps.InfoWindow();

var marker, i;

for (i = 0; i < locations.length; i++) {
marker = new google.maps.Marker({
position: new google.maps.LatLng(locations[i][1], locations[i][2]),
map: map
});

google.maps.event.addListener(marker, 'click', (function(marker, i) {
return function() {
infowindow.setContent(locations[i][0]);
infowindow.open(map, marker);
}
})(marker, i));
}
}

initialize()
</script>";

		} else {

		}
		?>

		</head>
		<body id="dashboard" onload="initialize()" >
		<!-- Top navigation bar -->
		<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
		<div class="container-fluid">
		<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a>
		<a class="brand" href="index.html">GPS Vehicle Location Tracker</a>
		<div class="btn-group pull-right">
		<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> <i class="icon-user"></i>

		<?php
		session_start();
		echo $_SESSION['fname'];
		?>

		<span class="caret"></span> </a>
		<ul class="dropdown-menu">
		<li>
		<a href="#">Profile</a>
		</li>
		<li>
		<a href="#">Settings</a>
		</li>
		<li>
		<a class="cookie-delete" href="#">Delete Cookies</a>
		</li>
		<li class="divider"></li>
		<li>
		<a href="./phpscript/loggout.php">Logout</a>
		</li>
		</ul>
		</div>
		<div class="nav-collapse">
		<ul class="nav">

		<li>
		<a href="#">Help</a>
		</li>
		</ul>
		</div><!--/.nav-collapse -->
		</div>
		</div>
		</div>
		<!-- Main Content Area | Side Nav | Content -->
		<div class="container-fluid">
		<div class="row-fluid">
		<!-- Side Navigation -->
		<div class="span2">
		<div class="member-box round-all">
		<a><img src="images/member_ph.png" class="member-box-avatar" /></a>
		<span> <strong>Administrator</strong>
		<br/>
		<a>
		<?php
		session_start();
		echo $_SESSION['fname'];
	?><
		/a>
		<br/>
		<span class="member-box-links"><a>Settings</a> | <a href="./phpscript/loggout.php">Logout</a></span> </span>
		</div>
		<div class="sidebar-nav">
		<div class="well" style="padding: 8px 0;">
		<ul class="nav nav-list">
		<li class="nav-header">
		Main
		</li>
		<li class="active">
		<a href="index.html"><i class="icon-home"></i> Dashboard</a>
		</li>
		<li>

		<li class="nav-header">
		Settings
		</li>

		</ul>
		</div>
		</div><!--/.well -->
		</div><!--/span-->

		<!-- Bread Crumb Navigation -->
		<div class="span10">
		<div>

		</div>

		<!-- Geographic Page Visit Map -->
		<div class="row-fluid">
		<div>
		<div id="map"
		style="width: 700px; height: 400px" class="span12"></div>
		</div>
		</div>

		</div><!--/span-->
		</div><!--/row-->

		<footer>
		<p>
		&copy; Anoj 2012
		</p>
		</footer>
		<div id="box-config-modal" class="modal hide fade in" style="display: none;">
		<div class="modal-header">
		<button class="close" data-dismiss="modal">
		×
		</button>
		<h3>Adjust widget</h3>
		</div>
		<div class="modal-body">
		<p>
		This part can be customized to set box content specifix settings!
		</p>
		</div>
		<div class="modal-footer">
		<a href="#" class="btn btn-primary" data-dismiss="modal">Save Changes</a>
		<a href="#" class="btn" data-dismiss="modal">Cancel</a>
		</div>
		</div>
		</div><!--/.fluid-container-->
		<!-- javascript Templates
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->

		<!-- Le javascript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<!-- Google API -->
		<script type="text/javascript" src="http://www.google.com/jsapi"></script>

		<!-- jQuery -->
		<script src="../../../ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

		<!-- Data Tables -->
		<script src="scripts/DataTables/media/js/jquery.dataTables.js"></script>

		<!-- jQuery UI Sortable -->
		<script src="scripts/jquery-ui/ui/minified/jquery.ui.core.min.js"></script>
		<script src="scripts/jquery-ui/ui/minified/jquery.ui.widget.min.js"></script>
		<script src="scripts/jquery-ui/ui/minified_/jquery.ui.mouse.min.js"></script>
		<script src="scripts/jquery-ui/ui/minified/jquery.ui.sortable.min.js"></script>
		<script src="scripts/jquery-ui/ui/minified/jquery.ui.widget.min.js"></script>

		<!-- jQuery UI Draggable & droppable -->
		<script src="scripts/jquery-ui/ui/minified/jquery.ui.draggable.min.js"></script>
		<script src="scripts/jquery-ui/ui/minified/jquery.ui.droppable.min.js"></script>

		<!-- Bootstrap -->
		<script src="bootstrap/js/bootstrap.min.js"></script>
		<script src="scripts/bootbox/bootbox.min.js"></script>

		<!-- Bootstrap Date Picker -->
		<script src="scripts/datepicker/js/bootstrap-datepicker.js"></script>

		<!-- jQuery Cookie -->
		<script src="scripts/jquery.cookie/jquery.cookie.js"></script>

		<!-- Full Calender -->
		<script type='text/javascript' src='scripts/fullcalendar/fullcalendar/fullcalendar.min.js'></script>

		<!-- CK Editor -->
		<script type="text/javascript" src="scripts/ckeditor/ckeditor.js"></script>
		<script type="text/javascript" src="scripts/ckeditor/adapters/jquery.js"></script>

		<!-- Chosen multiselect -->
		<script type="text/javascript" language="javascript" src="scripts/chosen/chosen/chosen.jquery.min.js"></script>

		<!-- Uniform -->
		<script type="text/javascript" language="javascript" src="scripts/uniform/jquery.uniform.min.js"></script>

		<!-- MultiFile Upload -->
		<!-- Error messages for the upload/download templates -->
		<script>
			var fileUploadErrors = {
				maxFileSize : 'File is too big',
				minFileSize : 'File is too small',
				acceptFileTypes : 'Filetype not allowed',
				maxNumberOfFiles : 'Max number of files exceeded',
				uploadedBytes : 'Uploaded bytes exceed file size',
				emptyResult : 'Empty file upload result'
			};
		</script>
		<!-- The template to display files available for upload -->
		<script id="template-upload" type="text/html">
			{% for (var i=0, files=o.files, l=files.length, file=files[0]; i<l; file=files[++i]) { %}
			<tr class="template-upload fade">
			<td class="preview"><span class="fade"></span></td>
			<td class="name">{%=file.name%}</td>
			<td class="size">{%=o.formatFileSize(file.size)%}</td>
			{% if (file.error) { %}
			<td class="error" colspan="2"><span class="label label-important">Error</span> {%=fileUploadErrors[file.error] || file.error%}</td>
			{% } else if (o.files.valid && !i) { %}
			<td>
			<div class="progress progress-success progress-striped active"><div class="bar" style="width:0%;"></div></div>
			</td>
			<td class="start">{% if (!o.options.autoUpload) { %}
			<button class="btn btn-primary">
			<i class="icon-upload icon-white"></i> Start
			</button>
			{% } %}</td>
			{% } else { %}
			<td colspan="2"></td>
			{% } %}
			<td class="cancel">{% if (!i) { %}
			<button class="btn btn-warning">
			<i class="icon-ban-circle icon-white"></i> Cancel
			</button>
			{% } %}</td>
			</tr>
			{% } %}
		</script>
		<!-- The template to display files available for download -->
		<script id="template-download" type="text/html">
			{% for (var i=0, files=o.files, l=files.length, file=files[0]; i<l; file=files[++i]) { %}
			<tr class="template-download fade">
			{% if (file.error) { %}
			<td></td>
			<td class="name">{%=file.name%}</td>
			<td class="size">{%=o.formatFileSize(file.size)%}</td>
			<td class="error" colspan="2"><span class="label label-important">Error</span> {%=fileUploadErrors[file.error] || file.error%}</td>
			{% } else { %}
			<td class="preview">{% if (file.thumbnail_url) { %}
			<a href="{%=file.url%}" title="{%=file.name%}" rel="gallery"><img src="{%=file.thumbnail_url%}"></a>
			{% } %}</td>
			<td class="name">
			<a href="{%=file.url%}" title="{%=file.name%}" rel="{%=file.thumbnail_url&&'gallery'%}">{%=file.name%}</a>
			</td>
			<td class="size">{%=o.formatFileSize(file.size)%}</td>
			<td colspan="2"></td>
			{% } %}
			<td class="delete">
			<button class="btn btn-danger" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}">
			<i class="icon-trash icon-white"></i> Delete
			</button>
			<input type="checkbox" name="delete" value="1">
			</td>
			</tr>
			{% } %}
		</script>
		<!-- The Templates plugin is included to render the upload/download listings -->
		<script src="../../../blueimp.github.com/JavaScript-Templates/tmpl.min.js"></script>
		<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
		<script src="../../../blueimp.github.com/JavaScript-Load-Image/load-image.min.js"></script>
		<!-- The Canvas to Blob plugin is included for image resizing functionality -->
		<script src="../../../blueimp.github.com/JavaScript-Canvas-to-Blob/canvas-to-blob.min.js"></script>
		<script src="../../../blueimp.github.com/Bootstrap-Image-Gallery/js/bootstrap-image-gallery.min.js"></script>
		<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
		<script src="scripts/blueimp-jQuery-File-Upload/js/jquery.iframe-transport.js"></script>
		<!-- The basic File Upload plugin -->
		<script src="scripts/blueimp-jQuery-File-Upload/js/jquery.fileupload.js"></script>
		<!-- The File Upload image processing plugin -->
		<script src="scripts/blueimp-jQuery-File-Upload/js/jquery.fileupload-ip.js"></script>
		<!-- The File Upload user interface plugin -->
		<script src="scripts/blueimp-jQuery-File-Upload/js/jquery.fileupload-ui.js"></script>
		<!-- The main application script -->
		<script src="scripts/blueimp-jQuery-File-Upload/js/main.js"></script>
		<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE8+ -->
		<!--[if gte IE 8]><script src="scripts/blueimp-jQuery-File-Upload/js/cors/jquery.xdr-transport.js"></script><![endif]-->

		<!-- Simplenso Scripts -->
		<script src="scripts/simplenso/simplenso.js"></script>
		</body>

		<!-- Mirrored from wbpreview.com/previews/WB00958H8/index.html by HTTrack Website Copier/3.x [XR&CO'2008], Tue, 14 Aug 2012 15:00:43 GMT -->
</html>
