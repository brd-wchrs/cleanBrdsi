<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

<!--(C) Bri Gainley, Alan Estrada, Nick St. Pierre, 2014 -->
<!-- With code from http://www.alessioatzeni.com/blog/signin-dropdown-box-like-twitter-with-jquery/ -->
<!-- for dropdown login menu -->

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>brdsi - A Twitter Analysis Tool</title>
        <meta name="description" content="Twitter trend analysis, delivered right to your screen">
        <meta name="viewport" content="width=device-width">
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <!-- build:css styles/vendor.css -->
        <!-- bower:css -->

        <!-- endbower -->
        <!-- endbuild -->
        <!-- build:css(.tmp) styles/main.css -->
        <link rel="stylesheet" href="styles/reset.css">
        <link rel="stylesheet" href="styles/main.css">
        <link rel="stylesheet" href="styles/statsTable.css">
        <link rel="icon" type="image/png" href="images/favicon.ico">

        <!-- Google Fonts -->
    		<link href='http://fonts.googleapis.com/css?family=Lato:100,300,400' rel='stylesheet' type='text/css'>
    		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800,600,300' rel='stylesheet' type='text/css'>

        <!-- mobile fix -->
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />

        <!-- inline css :3 -->
        <style type="text/css">
		  #map-canvas { height: 500px; width: 500px; /*inline-block*/}
          #dialog { display: none;}
          #tabl, #tabl td { border: black thin solid; padding: 0px; margin: 0px;}
          body { margin-top:  10px; /* leave room for the top of the page*/ }
          .pad{ padding: 10px;}
          .space{ margin: 10px;}
		    </style>

        <!-- jQuery -->
		    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        
        <!-- jQuery UI -->
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

        <!-- google maps -->
        <script type="text/javascript"
		            src="https://maps.googleapis.com/maps/api/js?libraries=geometry,drawing&key=AIzaSyBitVoCXwDIYznuU_fyzu-DukWsuY0ZMJA&sensor=false">
		    </script>

        <!-- google maps init (to be moved out of here) -->
        <script type="text/javascript">

		      // this will all be moved into a separate file, later.

		      // map contains the google maps map object, and needs this scope for
		      // ease of access.
		      var map;

          // print the date for my convenience
		      console.log(new Date());

		      // Use the radius and lat/long variables! They are updated automagically!
		      var activeCircle     =  null,  // this one must be inited to null, but
		          activeRadius     =  null,  // these three don't...
		          activeLatitude   =  null,
		          activeLongitude  =  null;


          function clog(message)
          {
            var out =  Date.now().toString() + " - message";
            window.console.log(out);
          }

          // creates a map and inserts it into the DOM
		      function initBasic(){

            // lat;lng object t contain the starting location on the google map
            //
            
            var startLoc = new google.maps.LatLng(42.3581, -71.0636); // BOSTON!

            
            if (navigator.geolocation)
            {
               navigator.geolocation.getCurrentPosition(function(position){
               startLoc =  new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
              });
            }
            
            

		        var mapOptions = {
		          center: startLoc,
		          zoom: 3
		        };


		        map = new google.maps.Map(document.getElementById("map-canvas"),
		                                  mapOptions);

		        // https://developers.google.com/maps/documentation/javascript/markers

		        // To add the marker to the map, use the 'map' property
		        /*var marker = new google.maps.Marker({
		            position:   startLoc,
		            map:        map,
		            title:      "Hello World!"
		        });*/


		      };

		      // initializes the drawing library functionality
		      function initDrawingLibrary(){


		        var drawingManager = new google.maps.drawing.DrawingManager( {

		        // options for the drawing manager
		          drawingControlOptions: {
		          position: google.maps.ControlPosition.TOP_CENTER,
		          drawingModes: [
		            //google.maps.drawing.OverlayType.MARKER,
		            google.maps.drawing.OverlayType.CIRCLE
		          ]
		        }});


		        // callback function called when a new circle is drawn.
		        function logCircle(c)
		        {

		          // remove the previous circle (IF THERE IS ONE)
		          if(activeCircle !== null){
		             activeCircle.setMap(null);
		          }

		          // we won't have access to this circle object later,
		          // so we gotta save a handle to it. (so we can remove it next call)
		          activeCircle = c;
		          activeRadius = c.getRadius();

		          var activeCenter =  c.getCenter();

		          activeLongitude  =  activeCenter.lng();
		          activeLatitude   =  activeCenter.lat();

		          // some lovely debug
		          console.log("logCircle callback.\nradius: " + activeRadius +
		                      "\ncenter: " + activeCenter );


		          // update our table!
		          /*$("#map-longitude").text(activeLongitude);
		          $("#map-latitude").text(activeLatitude);
		          $("#map-radius").text(activeRadius);*/

		        };

		        // add the drawing manager to the map
		        drawingManager.setMap(map);

		        // now that the drawing manager is in place, register a callback
		        // circlecomplete fires when the user draws a circle
		        google.maps.event.addListener(drawingManager, 'circlecomplete', logCircle);

		      };

          
          /** code to connect the map's coordinate and radius output with the
              twitter api **/
          function doServerStuff()
          {
            
            // don't attempt this unless we have values ready to go
            if( !(activeLongitude && activeLatitude && activeRadius) ){
              console.log("preventing an AJAX call because because lat/lng/rad data wasn't input");
            } else {              
            
              // inform the user of what's going on
              $("#trendDiv").html("<p>Loading trend data for your selection...</p>");


              // run trendLook.php and get it's output
              $.ajax({

                url: "trendLook.php",

                data:  { 
                          lat: activeLatitude,  
                          lng: activeLongitude,
                          rad: activeRadius
                        },


                success: function(data){
                  console.log("trendLook.php ajax success.");
                  $("#trendDiv").html(data);

                },


                error: function(jqXHR, errorStr){
                  console.log("trendLook.php ajax failed: " + errorStr);
                  $("#trendDiv").html("");

                },

                type: 'GET'

              });
            }
          };

          /**************  dialog functions *************/
          function dialogOK()
          {
            console.log("dialog: OK!!");
            $( "#dialog" ).dialog( "close" );
            doServerStuff();
          };


          function dialogCANCEL()
          {
            console.log("closing dialog map");
            $( "#dialog" ).dialog( "close" );
          };

          var mapAlive = false;
 
          function setupDialog()
          {
          
            if( ! mapAlive ){
                mapAlive = true;
                initialize(); 
            }
           
              $('#dialog').show();
              $('#dialog').dialog({
                width:      540,  
                height:     620,
                buttons:
                  [{
                    text:  "Find trends",
                    click:  dialogOK
                   },
                   {
                    text:  "Close map",
                    click:  dialogCANCEL
                   }]
              });
           
          };

		      // calls all initialization functions
		      function initialize() {
		          initBasic();
		          initDrawingLibrary();
		      };

		      //google.maps.event.addDomListener(window, 'load', initialize);

		    </script>

    </head>
    <body>

		<div class="container">
      <div id="top-navbar">
				<a href="bees.php"><img src="images/brdsiLogo.png"></a>
				<ul>
				  <li><a href="timeline.php">Timeline Analysis</a></li>
				  <li><a href="maps.php">Region Graph</a></li>
				  <li><a href="friendTrends.php">Friend Trends</a></li>
				</ul>
      </div>

	  <div id="content">
	  <div class="center">
		<h1><span class="dark">Regional Trends</span></h1>
		<!--aside>Drawing a Circle gets you a radius and Long/Lat. fk yesss</aside-->
        <!-- this div is the pop-over box. it isn't in the flow. styling it is not really needed, I think. -->
        <div id="dialog">
           <div id="map-canvas"><!-- the map will be created here --></div>
        </div>
        
        <!-- button that pops up the map -->
        <button class='pad space' onclick="setupDialog();">Expand Map</button>
		</div>
		
        <!-- debug object used to display the latitudes and longitudes from the map -->
        <div style='display:none;'>
				   <table id="tabl">
				     <tr>
				       <td>Longitude</td>
				       <td><p id="map-longitude"></p></td>
				     </tr>
				     <tr>
				       <td>Radius</td>
				       <td><p id="map-radius"></p></td>
				     </tr>
				     <tr>
				       <td>Latitude</td>
				       <td><p id="map-latitude"></p></td>
				     </tr>
				   </table>
				 </div>
        
         <div id='trendDiv' class="center"><p>Click the button to expand the map and draw a circle to find trends for that area.</p></div>
		</div>
		
      <div id="footer">
        <ul>
          <li><a href="about.html">About</a></li>
          <li><a href="contact.html">Contact</a></li>
          <li><a href="help.html">Help</a></li>
        </ul>
      </div>
		</div>

</body>
</html>
