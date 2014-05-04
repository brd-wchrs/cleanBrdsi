<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

<!--(C) Bri Gainley, Alan Estrada, Nick St. Pierre, 2014 -->
<!-- With code from http://www.alessioatzeni.com/blog/signin-dropdown-box-like-twitter-with-jquery/ -->
<!-- for dropdown login menu -->

<!--
  Nick
  april 14 2014
  using bri's and alan's code as a template

  home.php
-->

  <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>brdsi - A Twitter Analysis Tool</title>
        <meta name="description" content="Twitter trend analysis, delivered right to your screen">
        <meta name="viewport" content="width=device-width">

        <!-- build:css(.tmp) styles/main.css -->
        <link rel="stylesheet" href="styles/reset.css">
        <link rel="stylesheet" href="styles/home.css">
        <link rel="icon" type="image/png" href="images/favicon.ico">
        
        <!-- Google Fonts -->
        <link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,400italic,700' rel='stylesheet' type='text/css'>  
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800,600,300' rel='stylesheet' type='text/css'>      
                
        <!-- jQuery -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="scripts/honey.js"></script>

  </head>
  
  <body>
  	<div id="information">
  		<div id="container">
	  	<h1 id='title'> Welcome to Brdsi! </h1>
	  	<h2>
	  	  Brdsi (pronounced <span class="italic">bird's eye</span>) is a web application that gives an overview 
	  	  of Twitter users and trends from around the globe.
	  	</h2>
	  	<h3>
	  	  Our tools collect and analyze trends, users, common tags, frequency of tweets, and much, much more.
	  	</h3>
	  		<a href="bees.php">
	  		<div class="miniInfo">
	  			<img src="images/hexagons.png">
	  			<h4 class="">Current Trends</h4>
	  			<h5 class="center">View what's trending in cities across the U.S.</h5>
	  		</div>
	  		</a>
	  		<a href="timeline.php">
	  		<div class="miniInfo" id="barChart">
	  			<img src="images/barChart.png">
	  			<h4 class="">User Analysis</h4>
	  			<h5 class="center">Analyze stats on any Twitter user, including top mentions, hashtags, and daily usage</h5>
	  		</div>
	  		</a>
	  		<a href="maps.php">
	  		<div class="miniInfo" id="USA">
	  			<img src="images/USA.png">
	  			<h4 class="">Geo-Tweets</h4>
	  			<h5 class="center">Select a city or region of your interest and poll tweets and trends based on your selection</h5>
	  		</div>
	  		</a>
	  	</div>
  	</div>
	<div id="sidebar">
		<img src="images/brdWhite.png">
		<h1 class="center"> brdsi </h1>
		<h2 class="">A Twitter Analysis Tool</h2>
        <a class="btn" href="bees.php">Start Here</a>
     </div>
  </body>
</html>
