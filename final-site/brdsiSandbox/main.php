<?php
  session_start();
?><!doctype html>
<!--[if lt IE 7]>      <html ng-app="brdApp" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html ng-app="brdApp" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html ng-app="brdApp" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html ng-app="brdApp" class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>brdsi - A Twitter Newsfeed</title>
        <meta name="description" content="A newsfeed aggregating Twitter's trends, made just for you">
        <meta name="viewport" content="width=device-width">
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <!-- build:css styles/vendor.css -->
        <!-- bower:css -->

        <!-- endbower -->
        <!-- endbuild -->
        <!-- build:css(.tmp) styles/main.css -->
		<link rel="stylesheet" href="styles/reset.css">
		<link rel="stylesheet" href="styles/main.css">
		<link rel="stylesheet" href="styles/homeycombs.css">
		<link rel="icon" type="image/png" href="images/favicon.ico">

        <!-- Google Fonts -->
        <link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700' rel='stylesheet' type='text/css'>
        
        <!-- angular -->
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.9/angular.min.js"></script>
        <script src="scripts/angular-route.js"></script>
        <script src="scripts/angular-animate.min.js.js"></script>

    </head>
    <body>
		<div class="container">
	        <div id="top-navbar">
	          <a href="#"><img src="images/brdsiLogo.png"></a>
	          <ul>
	            <li><a href="#">Friend Trends</a></li>
	            <li><a href="#">Mood Graph</a></li>
	            <li><?php if(isset($_SESSION['username'])) 
                          echo '<a href="/twitteroauth/clearsessions.php" title="Logged in as '.$_SESSION['username'].'">Logout</a>';                        
                        else
                          echo '<a href="/twitteroauth/redirect.php">Login</a>';
              ?>
              </li>
	          </ul>
	        </div>

        <!-- this is where the main content gets injected -->
        <div ng-view></div>

	      <div id="footer">
          <ul>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
            <li><a href="#help">Help</a></li>
          </ul>
        </div>
      </div>
      
      <!-- this script controls the angular routing -->
      <script type='text/javascript'>
        // angular magick thanks to https://www.youtube.com/watch?v=tnXO-i7944M
        
        var app= angular.module('brdApp',['ngRoute', 'ngAnimate']);
        
        app.config(function($routeProvider){
          $routeProvider.when('/',
          {
            //controller:  'hexagonsController',
            templateUrl: 'hexaTrend.php'
          })
          .when('/about',
          {
            //controller: ''
            templateUrl: 'about.html'
          })
          .when('/trend/:trend',
          {
            //controller: ''
            templateUrl: function(routeparams){
              
              console.log(routeparams);
              console.log("trend is "+routeparams.trend);
              
              return 'trendLook.php?q='+routeparams.trend;
              
            }
          })
          .when('/contact',
          {
            //controller: ''
            templateUrl: 'contact.html'
          })
          .when('/help',
          {
            //controller: ''
            templateUrl: 'help.html'
          })
          
        });
  
        app.controller('aboutController', function($scope){
                    
        });
        app.controller('contactController', function($scope){
          
          
          
        });
        
      </script>
      
      <!-- for honeycombs -->
      <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script> 
      <script src="scripts/jquery.homeycombs.js"></script>
      
      <!-- For hexagon script -->
      <script type="text/javascript">
          $(document).ready(function() {
              $('.honeycombs').honeycombs({
              combWidth: 200,
              margin: 10
              });
           });
      </script>
</body>
</html>