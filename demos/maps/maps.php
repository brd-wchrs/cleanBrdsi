<!DOCTYPE html>
  <!--

  Nicholas St.Pierre

  March 2, 2014

  This is a maps example adapted from the "Hello, World" example code at
  https://developers.google.com/maps/documentation/javascript/tutorial

  -->
  <html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #map-canvas { height: 500px; width: 500px; border: orange thick solid; display:inline-block}
      #tabl, #tabl td { border: black thin solid; padding: 0px; margin: 0px;}
    </style>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script type="text/javascript"
            src="https://maps.googleapis.com/maps/api/js?libraries=geometry,drawing&key=AIzaSyBitVoCXwDIYznuU_fyzu-DukWsuY0ZMJA&sensor=false">
    </script>
    <script type="text/javascript">

      // this will all be moved into a separate file, later.

      // map contains the google maps map object, and needs this scope for
      // ease of access.
      var map;

      // creates a map and inserts it into the DOM
      function initBasic(){

        var startLoc=new google.maps.LatLng(-34.397, 150.644);

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

      // print the date for my convenience
      console.log(new Date());
      
      
      // Use the radius and lat/long variables! They are updated automagically!
      var activeCircle     =  null,  // this one must be inited to null, but
          activeRadius     =  null,  // these three don't...
          activeLatitude   =  null,
          activeLongitude  =  null;
      
      
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
          $("#map-longitude").text(activeLongitude);
          $("#map-latitude").text(activeLatitude);
          $("#map-radius").text(activeRadius);


        };

        // add the drawing manager to the map
        drawingManager.setMap(map);

        // now that the drawing manager is in place, register a callback
        // circlecomplete fires when the user draws a circle
        google.maps.event.addListener(drawingManager, 'circlecomplete', logCircle);


      };


      // calls all initialization functions
      function initialize() {
          initBasic();
          initDrawingLibrary();
      };

      google.maps.event.addDomListener(window, 'load', initialize);



    </script>
  </head>
  <body>
    <h1>Google Maps Circle Example</h1>
    <aside>Drawing a Circle gets you a radius and Long/Lat. fk yesss</aside>
    <div id="map-canvas"><!-- the map will be created here --></div>
    <div>
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
  </body>
</html>