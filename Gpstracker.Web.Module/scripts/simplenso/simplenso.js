// Init vars
var closedBoxesCookiePrefix = "SimplensoClosedBoxes_";
var boxPositionCookiePrefix = "SimplensoColumnBoxes_";
var deletedBoxesCookiePrefix = "SimplensoDeletedBoxes_";
var themeCookie = "SimplensoTheme";
var menuCookie = "SimplensoMenu";
var cookieExpiration = 365;

var screenWidth = $(document).width();
var screenHeigth =  $(document).height();

// check for theme cookie first and switch theme
$(document).ready(function(){
	var themeCkie = $.cookie(themeCookie);
	if (themeCkie && themeCkie != '')	{
		switchTheme(null, themeCkie);
	}
	
	// menu cookie
	var menuCkie = $.cookie(menuCookie);
	if (menuCkie && menuCkie != '')	{
		
		if(menuCkie == 2) {
			$(".sidenav-style-2").parent().parent().attr("class","nav nav-tabs nav-stacked");		
			$(".sidenav-style-2").parent().parent().parent().removeClass("well");
		}
	}
});

// theme switcher function 
function switchTheme(e, name) {
	if(name != null) {
		var themeName = name;
	} else {
		var className = e.attr("class");
		var themeName = className.replace("theme-switch-", "");
	}
	if(themeName != "default") {
		$("#main-theme-script").attr('href', "css/themes/" + themeName + "/bootstrap.min.css");
		$("#theme-specific-script").attr('href', "css/themes/" + themeName + "/simplenso." + themeName + ".css");
	} else {
		$("#main-theme-script").attr('href', "bootstrap/css/bootstrap.css");
		$("#theme-specific-script").attr('href', "css/themes/" + themeName + ".css");		
	}
	
	$.cookie(themeCookie, themeName, { expires: cookieExpiration });
}

$(document).ready(function(){
	// theme switch event
	$("[class^=theme-switch-]").click(function(e){
		var e = $(this);
		switchTheme(e, null);
	});
	
	// switch menu style
	$(".sidenav-style-1").click(function(e){
		// modify overlaying div
		$(this).parent().parent().attr("class","nav nav-list");
		if(!$(this).parent().parent().parent().hasClass("well")) {
			$(this).parent().parent().parent().addClass("well");		
		}
		$.cookie(menuCookie, "1", { expires: cookieExpiration });
	});
	
	// switch menu style
	$(".sidenav-style-2").click(function(e){
		// modify overlaying div
		$(this).parent().parent().attr("class","nav nav-tabs nav-stacked");		
		$(this).parent().parent().parent().removeClass("well");
		$.cookie(menuCookie, "2", { expires: cookieExpiration });		
	});
	
	// Control funtion for portlet (box) buttons clicks
	function setControls(ui) {		
		//$('[class="box-btn"][title="toggle"]').click(function() {
		$('.box-btn').click(function() {
			var e = $(this);
			//var p = b.next('a');
			// Control functionality
			switch(e.attr('title').toLowerCase()) {
				case 'config':
					widgetConfig(b, p);
					break;
				
				case 'toggle':
					widgetToggle(e);
					break;
				
				case 'close':
					widgetClose(e);
					break;
			}
		});
	}
	
	// Toggle button widget
	function widgetToggle(e) {
		// Make sure the bottom of the box has rounded corners
		e.parent().toggleClass("round-all");
		e.parent().toggleClass("round-top");
		
		// replace plus for minus icon or the other way around
		if(e.html() == "<i class=\"icon-plus\"></i>") {
			e.html("<i class=\"icon-minus\"></i>");
		} else {
			e.html("<i class=\"icon-plus\"></i>");
		}
		
		// close or open box	
		e.parent().next(".box-container-toggle").toggleClass("box-container-closed");
		
		// store closed boxes in cookie
		var closedBoxes = [];
		var i = 0;
		$(".box-container-closed").each(function() 
		{
				closedBoxes[i] = $(this).parent(".box").attr("id");
				i++;		
		});
		$.cookie(closedBoxesCookiePrefix + $("body").attr("id"), closedBoxes, { expires: cookieExpiration });
        
		//Prevent the browser jump to the link anchor
		return false; 
		
	}
	
	// Close button widget with dialog
	function widgetClose(e) {
		// get box element
		var box = e.parent().parent();
		
		// prompt user to confirm
		bootbox.confirm("Are you sure?", function(confirmed) {
			// remove box
			box.remove();
			
			// store removal in cookie
			$.cookie(deletedBoxesCookiePrefix + $("body").attr("id") + "_" + box.attr('id'), "yes", { expires: cookieExpiration });
   		});	
	}
	
	$('#box-close-modal .btn-success').click(function(e) {
		   // e is the element that triggered the event
		   console.log(e.target); // outputs an Object that you can then explore with Firebug/developer tool.
		   // for example e.target.firstChild.wholeText returns the text of the button
		});
	
	// Modify button widget
	function widgetConfig(w, p) {		
		$("#dialog-config-widget").dialog({
			resizable: false,
			modal: true,
			width: 500,
			buttons: {
				"Save changes": function(e, ui) {
					/* code the functionality here, could store in a cookie */					
					$(this).dialog("close");
				},
				Cancel: function() {					
					$(this).dialog("close");
				}
			}
		});
	}$('#tab').tab('show');
	
	// set portlet comtrols
	setControls();
	
	// Portlets (boxes)
    $(".column").sortable({
        connectWith: '.column',
		iframeFix: false,
		items:'div.box',	
		opacity:0.8,
		helper:'original',
		revert:true,
		forceHelperSize:true,	
		placeholder: 'box-placeholder round-all',
		forcePlaceholderSize:true,
		tolerance:'pointer'
    });
	
	// Store portlet update (move) in cookie
    $(".column").bind('sortupdate', function() {
        $('.column').each(function() {
            $.cookie(boxPositionCookiePrefix + $("body").attr("id") + ($(this).attr('id')), $(this).sortable('toArray'), { expires: cookieExpiration });
        });
    });
	
	// Portlets | INIT | check for closed portlet cookie
	var ckie = $.cookie(closedBoxesCookiePrefix+$("body").attr("id"));
	if (ckie && ckie != '')	{
		// get cookie and split in array
		var list = ckie.split(',');
		
		// loop over boxes in cookie and do actions
		for (var x = 0; x < list.length; x++) {	
		 	var box = $("#"+list[x]);
			// close box
			box.find(".box-container-toggle").toggleClass("box-container-closed");
			// make closed box round
			box.find(".box-header").toggleClass("round-top").toggleClass("round-all");
			// find toggle button and change icon
			box.find('a[title="toggle"]').html("<i class=\"icon-plus\"></i>");
		}
	}
	
	/* Portlets | INIT | check for porlet order cookies	
    for (var i = 0; i < $(".box").size(); i++) {
		// get the cooke containing the 
        var ckie = $.cookie(boxPositionCookiePrefix+ $("body").attr("id") + "col"+i);

        if (ckie && ckie != ''){
            var list = ckie.split(',');

            for (var x = 0; x < list.length; x++) {
               $('#'+list[x]).appendTo('#col' + i);			  
            }
        }
    }

	/* Portlets | INIT | Deleted boxes stored in cookie
	$(".box").each(function() {	
		// get id for each box	
		var id = $(this).attr("id");

		// get cookie for that box
		var ckie = $.cookie(deletedBoxesCookiePrefix + $("body").attr("id" )+ "_" + id);
		
		// check if cookie exist if so delete box from screen
		if (ckie && ckie != '') {
			$(this).remove();
		}
	});*/
	
	// Tooltip 
	$('a[rel=tooltip]').tooltip();
	
	// Popovers
	$('[rel=popover]').popover();
	
	// Datepicker 
	$(".datepicker").datepicker();
	
	// ckeditor
	$( 'textarea.html_editor_on_simple' ).ckeditor( { 
		toolbar : 'Basic', 
		language : 'en',
		uiColor : '#F9F9F9',
		resize_dir: 'vertical',
		toolbarCanCollapse: true,
		pasteFromWordRemoveFontStyles: true,
		pasteFromWordRemoveStyles: true,
		pasteFromWordPromptCleanup:true,
		scayt_autoStartup:true,
		scayt_sLang:'nl_NL',
		width:'95%',
		toolbar_Basic : [
			{ name: 'document', items : [ 'Source'] },
			{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
			{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
			{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
			{ name: 'tools', items : [ 'Maximize', 'ShowBlocks' ] }
		]
	} );
	
	// Uniform
	$(".uniform_on").uniform();
	
	// Chosen multiselect
	$(".chzn-select").chosen();
	
	// Delete Cookies
	$(".cookie-delete").click(function() {
	  deleteCookies();
	});
			
	// funtion to get all cookies
	function getCookiesArray() {
	    var cookies = { };
	 
		if (document.cookie && document.cookie != '') {
	        var split = document.cookie.split(';');
        	for (var i = 0; i < split.length; i++) {
	            var name_value = split[i].split("=");
	            name_value[0] = name_value[0].replace(/^ /, '');
	            cookies[decodeURIComponent(name_value[0])] = decodeURIComponent(name_value[1]);
	        }
	    }
 
	    return cookies;  
	}
	
	// function to delete all cookies
	function deleteCookies() {
		var cookies = getCookiesArray();
		for(var name in cookies) {
		  $.cookie(name, null);
		}
	}
});

/* Dashboard page */
// Load google chart script
google.load('visualization', '1', {packages: ['geochart']});
google.load("visualization", "1", {packages:["corechart"]});
google.load('visualization', '1', {packages:['gauge']});

/* index.html specific script */
$(document).ready(function(){	
	if($("body").attr("id") == "dashboard") {
		// Geo Map Chart
		var mapWidth = Math.round(((screenWidth / 12) * 10) * 0.8);
		var mapHeight = Math.round(mapWidth * 0.5);

		$('#dashboard-visit-map').width(mapWidth*1.1);
		$('#dashboard-visit-map').height(mapHeight*1.1);

		var data = google.visualization.arrayToDataTable([
		  ['Country', 'Page Hits'],
		  ['Germany', 3000],
		  ['United States', 4500],
		  ['Brazil', 11000],
		  ['Canada', 9000],
		  ['France', 14000],
		  ['RU', 8700],
		  ['NL', 20000]
		]);
	  
		var geochart = new google.visualization.GeoChart(
			document.getElementById('dashboard-visit-map'));
		geochart.draw(data, {width: mapWidth, height: mapHeight,backgroundColor: { fill:'transparent' }});
		  
		// Pie Chart
		var data = google.visualization.arrayToDataTable([
			['Browser', 'Usage'],
			['Internet Explorer', 11],
			['Google Chrome', 2],
			['Opera',  2],
			['Safari', 2],
			['FireFox', 7]
		  ]);

		  var options = {
			title: 'Browser Usage',
		    forceIFrame: false,
			backgroundColor: { fill:'transparent' }
		  };
  
		  var chart = new google.visualization.PieChart(document.getElementById('dashboard-browser-chart'));
		  chart.draw(data, options);
			
		// Line Chart
		var data = google.visualization.arrayToDataTable([
          ['Date', 'Hits', 'Unique'],
          ['Jan',  1000,      400],
          ['Feb',  1170,      460],
          ['Mar',  660,       1120],
          ['Apr',  1030,      540],
		  ['May',  1330,      540]
        ]);

        var options = {
          title: 'Website Visits',
		  forceIFrame: false,
		  backgroundColor: { fill:'transparent' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('dashboard-visit-chart'));
        chart.draw(data, options);
	
		// Gauge Chart
		
		var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['Registrations', 80],
          ['Comments', 55],
		  ['Reviews', 34]
        ]);
		
        var options = {
          width: Math.round(($('#dashboard-visit-chart').width())),
		  height: $('#dashboard-visit-chart').height()-30, 
          redFrom: 90, redTo: 100,
          yellowFrom:75, yellowTo: 90,
          minorTicks: 5,
		  forceIFrame: false,
		  backgroundColor: { fill:'transparent' }
        };

        var chart = new google.visualization.Gauge(document.getElementById('dashboard-new-registrations-gauge-chart'));
        chart.draw(data, options);
		
		$('#dashboard-new-registrations-gauge-chart').height($('#dashboard-visit-chart').height()-30);
		
		// Calendar
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		
		$('#calendar').fullCalendar({
			theme: false,
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay '
			},
			 
			aspectRatio: 3,
			editable: true,
			events: [
				{
					title: 'All Day Event',
					start: new Date(y, m, 1)
				},
				{
					title: 'Long Event',
					start: new Date(y, m, d-5),
					end: new Date(y, m, d-2)
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: new Date(y, m, d-3, 16, 0),
					allDay: false
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: new Date(y, m, d+4, 16, 0),
					allDay: false
				},
				{
					title: 'Meeting',
					start: new Date(y, m, d, 10, 30),
					allDay: false
				},
				{
					title: 'Lunch',
					start: new Date(y, m, d, 12, 0),
					end: new Date(y, m, d, 14, 0),
					allDay: false
				},
				{
					title: 'Birthday Party',
					start: new Date(y, m, d+1, 19, 0),
					end: new Date(y, m, d+1, 22, 30),
					allDay: false
				},
				{
					title: 'Click for Google',
					start: new Date(y, m, 28),
					end: new Date(y, m, 29),
					url: 'http://google.com/'
				}
			]
		});
	}
	
});

/* portlets.html specific script */
$(document).ready(function(){	
	if($("body").attr("id") == "portlets") {
		var boxHeight = $("#box-4").find('.box-content').height();
		$('.box-content').height(boxHeight);
		
	}
});

/* typography.html specific script */
$(document).ready(function(){	
	if($("body").attr("id") == "typography") {
		//Pretty print make code pretty
   		window.prettyPrint && prettyPrint();		
	}
});

/* gallery.html specific script */
$(document).ready(function(){	
	if($("body").attr("id") == "gallery") {
		'use strict';
	
		// Start slideshow button:
		$('#start-slideshow').button().click(function () {
			var options = $(this).data(),
				modal = $(options.target),
				data = modal.data('modal');
			if (data) {
				$.extend(data.options, options);
			} else {
				options = $.extend(modal.data(), options);
			}
			modal.find('.modal-slideshow').children()
				.removeClass('icon-play')
				.addClass('icon-pause');
			modal.modal(options);
		});
	
		// Toggle fullscreen button:
		$('#toggle-fullscreen').button().click(function () {
			var button = $(this),
				root = document.documentElement;
			if (!button.hasClass('active')) {
				$('#modal-gallery').addClass('modal-fullscreen');
				if (root.webkitRequestFullScreen) {
					root.webkitRequestFullScreen(
						window.Element.ALLOW_KEYBOARD_INPUT
					);
				} else if (root.mozRequestFullScreen) {
					root.mozRequestFullScreen();
				}
			} else {
				$('#modal-gallery').removeClass('modal-fullscreen');
				(document.webkitCancelFullScreen ||
					document.mozCancelFullScreen ||
					$.noop).apply(document);
			}
		});
	
		/* Load images via flickr for demonstration purposes:
		$.ajax({
			url: 'http://api.flickr.com/services/rest/',
			data: {
				format: 'json',
				method: 'flickr.interestingness.getList',
				api_key: '7617adae70159d09ba78cfec73c13be3'
			},
			dataType: 'jsonp',
			jsonp: 'jsoncallback'
		}).done(function (data) {
			var gallery = $('#gallery'),
				url;
			$.each(data.photos.photo, function (index, photo) {
				url = 'http://farm' + photo.farm + '.static.flickr.com/' +
					photo.server + '/' + photo.id + '_' + photo.secret;
				$('<a rel="gallery"/>')
					.append($('<img>').prop('src', url + '_s.jpg'))
					.prop('href', url + '_b.jpg')
					.prop('title', photo.title)
					.appendTo(gallery);
			});
		});*/		
	}
});
	
/* calendar.html specific script */
$(document).ready(function(){	
	if($("body").attr("id") == "bigcalendar") {
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
	
		
   		/* initialize the external events
		-----------------------------------------------------------------*/
		$('#external-events div.external-event').each(function() {
			// create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
			// it doesn't need to have a start or end
			var eventObject = {
				title: $.trim($(this).text()) // use the element's text as the event title
			};			
		
			// store the Event Object in the DOM element so we can get to it later
			$(this).data('eventObject', eventObject);		
		
			// make the event draggable using jQuery UI
			$(this).draggable({
				zIndex: 999,
				revert: true,     // will cause the event to go back to its
				revertDuration: 0  //  original position after the drag
			});			
		});
		
		
		/* initialize the calendar
		-----------------------------------------------------------------*/	
		$('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		editable: true,
		theme:false,
		droppable: true, // this allows things to be dropped onto the calendar !!!
		drop: function(date, allDay) { // this function is called when something is dropped
			// retrieve the dropped element's stored Event Object
			var originalEventObject = $(this).data('eventObject');
			// we need to copy it, so that multiple events don't have a reference to the same object
			var copiedEventObject = $.extend({}, originalEventObject);
			// assign it the date that was reported
			copiedEventObject.start = date;
			copiedEventObject.allDay = allDay;
			// render the event on the calendar
			// the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
			$('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
			// is the "remove after drop" checkbox checked?
			if ($('#drop-remove').is(':checked')) {
				// if so, remove the element from the "Draggable Events" list
				$(this).remove();
			}
		}
		});	
	}
});

/* DataTables Extensions for Bootstrap */
/* Default class modification */
$.extend( $.fn.dataTableExt.oStdClasses, {
	"sSortAsc": "header headerSortDown",
	"sSortDesc": "header headerSortUp",
	"sSortable": "header"
} );

/* API method to get paging information */
$.fn.dataTableExt.oApi.fnPagingInfo = function ( oSettings )
{
	return {
		"iStart":         oSettings._iDisplayStart,
		"iEnd":           oSettings.fnDisplayEnd(),
		"iLength":        oSettings._iDisplayLength,
		"iTotal":         oSettings.fnRecordsTotal(),
		"iFilteredTotal": oSettings.fnRecordsDisplay(),
		"iPage":          Math.ceil( oSettings._iDisplayStart / oSettings._iDisplayLength ),
		"iTotalPages":    Math.ceil( oSettings.fnRecordsDisplay() / oSettings._iDisplayLength )
	};
}

/* Bootstrap style pagination control */
$.extend( $.fn.dataTableExt.oPagination, {
	"bootstrap": {
		"fnInit": function( oSettings, nPaging, fnDraw ) {
			var oLang = oSettings.oLanguage.oPaginate;
			var fnClickHandler = function ( e ) {
				e.preventDefault();
				if ( oSettings.oApi._fnPageChange(oSettings, e.data.action) ) {
					fnDraw( oSettings );
				}
			};

			$(nPaging).addClass('pagination').append(
				'<ul>'+
					'<li class="prev disabled"><a href="#">&larr; '+oLang.sPrevious+'</a></li>'+
					'<li class="next disabled"><a href="#">'+oLang.sNext+' &rarr; </a></li>'+
				'</ul>'
			);
			var els = $('a', nPaging);
			$(els[0]).bind( 'click.DT', { action: "previous" }, fnClickHandler );
			$(els[1]).bind( 'click.DT', { action: "next" }, fnClickHandler );
		},

		"fnUpdate": function ( oSettings, fnDraw ) {
			var iListLength = 5;
			var oPaging = oSettings.oInstance.fnPagingInfo();
			var an = oSettings.aanFeatures.p;
			var i, j, sClass, iStart, iEnd, iHalf=Math.floor(iListLength/2);

			if ( oPaging.iTotalPages < iListLength) {
				iStart = 1;
				iEnd = oPaging.iTotalPages;
			}
			else if ( oPaging.iPage <= iHalf ) {
				iStart = 1;
				iEnd = iListLength;
			} else if ( oPaging.iPage >= (oPaging.iTotalPages-iHalf) ) {
				iStart = oPaging.iTotalPages - iListLength + 1;
				iEnd = oPaging.iTotalPages;
			} else {
				iStart = oPaging.iPage - iHalf + 1;
				iEnd = iStart + iListLength - 1;
			}

			for ( i=0, iLen=an.length ; i<iLen ; i++ ) {
				// Remove the middle elements
				$('li:gt(0)', an[i]).filter(':not(:last)').remove();

				// Add the new list items and their event handlers
				for ( j=iStart ; j<=iEnd ; j++ ) {
					sClass = (j==oPaging.iPage+1) ? 'class="active"' : '';
					$('<li '+sClass+'><a href="#">'+j+'</a></li>')
						.insertBefore( $('li:last', an[i])[0] )
						.bind('click', function (e) {
							e.preventDefault();
							oSettings._iDisplayStart = (parseInt($('a', this).text(),10)-1) * oPaging.iLength;
							fnDraw( oSettings );
						} );
				}

				// Add / remove disabled classes from the static elements
				if ( oPaging.iPage === 0 ) {
					$('li:first', an[i]).addClass('disabled');
				} else {
					$('li:first', an[i]).removeClass('disabled');
				}

				if ( oPaging.iPage === oPaging.iTotalPages-1 || oPaging.iTotalPages === 0 ) {
					$('li:last', an[i]).addClass('disabled');
				} else {
					$('li:last', an[i]).removeClass('disabled');
				}
			}
		}
	}
} );

/* Table initialisation */
$(document).ready(function() {
	$('.bootstrap-datatable').dataTable( {
		"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
		"sPaginationType": "bootstrap",
		"oLanguage": {
			"sLengthMenu": "_MENU_ records per page"
		}
	} );
} );