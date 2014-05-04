<html>
  <head>
    <!-- 
      File: wordcloud.php
      Author: Alan Estrada
    
      Comments because HEINES MAKES ME FEEL BAD 
      
      Literally for just messing around with the basic word cloud
      making.
      
      Using someone else's class TwitterAPIExchange.php for OAuth.
      -->

    <title>Testing Word Cloud</title>

    <!-- Oh, hi, this links to a very lazily done css file -->
    <link rel="stylesheet" type="text/css" href="twitter.css">
  </head>
  <body>

    <p>If you can see it, this works</p>

    <?php

       
       require_once('wordCloudMaker.php');
       /*
       $response = Unirest::post(
         "https://gatheringpoint-word-cloud-maker.p.mashape.com/index.php",
         array(
           "X-Mashape-Authorization" => "OkVWcztYb4IzHPzPd41C7uo1xWWaaB1s"
         ),
         array(
           "height" => 800,
           "textblock" => "yolo hello yolo swag what oh my god urban yolo swag urban outfitters hello there piano hello yolo swag urban",
           "width" => 800,
           "config" => "n\/a"
        )
      );
      */
      $text = "yolo hello yolo swag what oh my god urban yolo swag urban outfitters hello there piano hello yolo swag urban" ;
      echo generateWordCloud( $text, 800, 800 ) ;
    

    ?>

    

  </body>
</html>

