// map contains the google maps map object, and needs this scope for
  // ease of access.
  var map;


  // Use the radius and lat/long variables! They are updated automagically!
  var activeCircle     =  null,  // this one must be inited to null, but
      activeRadius     =  null,  // these three don't...
      activeLatitude   =  null,
      activeLongitude  =  null;


  // if you feel cozier using functions over accessing variables, well here you go:
  function getMapsLatitude(){   return activeLatitude;  }
  function getMapsLongitude(){  return activeLongitude; }
  function getMapsRadius(){     return activeRadius;    }


  // creates a map and inserts it into the DOM
  // pass to it the ID of the DIV you want the map to be created in
  function initBasic(map_dom_id){

    // controls where on earth the map begins at
    var startLoc=new google.maps.LatLng(-34.397, 150.644);

    var mapOptions = {
      center: startLoc,
      zoom:   3
    };

    map = new google.maps.Map(document.getElementById(map_dom_id),
                              mapOptions);

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

      // get the latitude and longitude values from the lat/long maps object
      activeLongitude  =  activeCenter.lng();
      activeLatitude   =  activeCenter.lat();

      // some lovely debug:
      //console.log("logCircle callback.\nradius: " + activeRadius +
      //            "\ncenter: " + activeCenter );


      /// note you dont need to do this, this is just an example of using the values we have on the page
      //$("#map-longitude").text(activeLongitude);
      //$("#map-latitude").text(activeLatitude);
      //$("#map-radius").text(activeRadius);


    };

    // add the drawing manager to the google map
    drawingManager.setMap(map);

    // now that the drawing manager is in place, register this callback:
    // the event 'circlecomplete' fires when the user draws a circle
    google.maps.event.addListener(drawingManager, 'circlecomplete', logCircle);


  };


  // calls all initialization functions
  function map_initialize() {
      initBasic("map-canvas"); // ID of the DIV you want the MAP to be generated in (it clobbers/takes up the whole div)
      initDrawingLibrary();
  };


  // this makes our initialize function fire when the window is loaded.
  //  we might want to change this .... say to fire only when a user asks for it?
  //google.maps.event.addDomListener(window, 'load', map_initialize);