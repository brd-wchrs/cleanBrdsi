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
        <!-- build:css styles/vendor.css -->
        <!-- bower:css -->

        <!-- endbower -->
        <!-- endbuild -->
        <!-- build:css(.tmp) styles/main.css -->
        <link rel="stylesheet" href="styles/reset.css">
        <link rel="stylesheet" href="styles/main.css">
        <link rel="stylesheet" href="styles/homeycombs.css">
        <link rel="stylesheet" href="styles/statsTable.css">
        <link rel="icon" type="image/png" href="images/favicon.ico">
        
        <!-- Google Fonts -->
		<link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700' rel='stylesheet' type='text/css'>        
        <!-- endbuild -->
        <!--script src="bower_components/modernizr/modernizr.js"></script-->
        
        <!-- angular -->
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.9/angular.min.js"></script>
        <script src="scripts/angular-route.js"></script>
        
        <!-- jQuery -->
           <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <!--script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script--> 
        <script src="scripts/honey.js"></script>
        <!--script src="scripts/jquery.homeycombs.js"></script-->

        <!-- For hexagon script -->
        <script type="text/javascript">
            //$(document).ready(
             function homeyComb() {
                console.log("Running homeyComb!!!!!");
                $('.honeycombs').honeycombs({
                combWidth: 200,
                margin: 10
                });
             }//);
        </script>        
    </head>
    
    <body ng-app='brdApp'>
	        <div id="top-navbar">
		          <a href="brd.php"><img src="images/brdsiLogo.png"></a>
		          <ul>
		            <li><a href="timeline.php">Timeline Analysis</a></li>
		            <li><a href="maps.php">Region Graph</a></li>
		            <li><a href="friendTrends.php">Friend Trends</a></li>
		          </ul>
	          </div>
	        <div ng-view class="content">
            <script>
                // angular magick thanks to https://www.youtube.com/watch?v=tnXO-i7944M

                var app= angular.module('brdApp', ['ngRoute']);

                app.config(function($routeProvider){
                  $routeProvider.when('/',
                  {
                    controller:  'hexagonsController',
                    templateUrl: 'pages/trendCache.php'
                  })
                  .when('/timeline',
                  {
                    templateUrl: 'timeline.php'
                  })
                  .when('/maps',
                  {
                    templateUrl: 'maps.php'
                  })
                  .when('/friendTrends',
                  {
                    templateUrl: 'friendTrends.php'
                  })
                  .when('/trend/:trend',
                  {
                    // the :trends is a unique thing: it takes whatever string follows /trend/ in the url and saves it in a variable for us
                    templateUrl: function(routeparams){

                      console.log(routeparams);
                      console.log("trend is "+routeparams.trend);

                      return 'tweetLook.php?q='+routeparams.trend;

                    }
                  })
                  .when('/about',
                  {
                    templateUrl: 'about.html'
                  })
                  .when('/contact',
                  {
                    templateUrl: 'contact.html'
                  })
                  .when('/help',
                  {
                    templateUrl: 'help.html'
                  })
                  .otherwise('/');

                });

                // this controller waits for success, and then runs a setup function
                // for the homeycombs
                app.controller('hexagonsController', [ '$scope', 
                        function($scope){
                          console.log("running hexagonsController");

                          $scope.$on('$viewContentLoaded', function() {
                               console.log("viewcontentLoaded done!!!");
                               homeyComb();
                          });
                        }]);

            </script>
	        </div>
	        
	        <div id="footer">
	        	<ul>
              <li><a href="about.html">About</a></li>
              <li><a href="contact.html">Contact</a></li>
              <li><a href="help.html">Help</a></li>        	
            </ul>
          </div>
</body>
</html>
