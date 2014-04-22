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
        <link rel="stylesheet" href="styles/main.css">
        <link rel="icon" type="image/png" href="images/favicon.ico">
        
        <!-- Google Fonts -->
        <link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700' rel='stylesheet' type='text/css'>  
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800,600,300' rel='stylesheet' type='text/css'>      
                
        <!-- jQuery -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="scripts/honey.js"></script>

  </head>
  <body>
    <div id="top-navbar">
        <a href="bees.php"><img src="images/brdsiLogo.png"></a>
        <ul>
          <li><a href="timeline.php">Timeline Analysis</a></li>
          <li><a href="maps.php">Region Graph</a></li>
          <li><a href="friendTrends.php">Friend Trends</a></li>
        </ul>
      </div>

    <div id='content' class="center">
      <h1 id='title'> Welcome to Brdsi </h1>
      <p>
        Brdsi (pronounced bird's eye) is a web application that gives an overview 
        of Twitter users and trends from around the globe.
      </p>
      <p>
        It contains a collection of tools to gather and display
        high-level information from Twitter.
      </p>
      <div id='letsGo' class="center">
        <a href="brd.php">Click here to get started</a>
      </div>
    </div>
  </body>
</html>
