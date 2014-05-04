<html>
  <head>
    <!-- 
      File: cities.php
      Author: Alan Estrada
    
      Comments because HEINES MAKES ME FEEL BAD 
      
      Literally for just messing around with the basic twitter API.
      This one is for grabbing trending information for Bahstan, LA
      and Atlanta.
      
      Using someone else's class TwitterAPIExchange.php for OAuth.
      -->

    <title>Testing Twitter</title>

    <!-- Oh, hi, this links to a very lazily done css file -->
    <link rel="stylesheet" type="text/css" href="twitter.css">
  </head>
  <body>

    <?php

    require_once('TwitterAPIExchange.php');

    /** Set access tokens here - see: https://dev.twitter.com/apps/ **/
    $settings = array(
      'oauth_access_token' => "2326287732-4iSDtAQB7nhc7EsvxFNUgOBg5LJpdqYvbQZEP0C",
      'oauth_access_token_secret' => "pBJ4aPRnfkEBBAqOUK9k8GDf7LHJBD2xPV4w0tmphYUhf",
      'consumer_key' => "y7ojWz9W1EAqeFyK5BsHfA",
      'consumer_secret' => "atcvh1uWEdNNZ2hnlUrcrFuE1yTqlnRHDpToVxik"
    );

    $requestMethod = 'GET' ;
    $twitter = new TwitterAPIExchange($settings);

    /** Arrays that contain the trends of each city **/
    $trendsBoston = array() ;
    $trendsLA = array() ;
    $trendsAtlanta = array() ;

    /** Now we create the URL that requests the trends of the WOEid **/
    $url = 'https://api.twitter.com/1.1/trends/place.json';

    /** Get the trends in Boston **/
    $getfield = '?id=2367105' ;
    $trendsJSON = json_decode( $twitter->setGetfield($getfield)
                               ->buildOauth($url, $requestMethod)
                                ->performRequest(),true);

    /** Populate trendsBoston with the trends of Boston **/
    foreach ( $trendsJSON[0]['trends'] as $trend) {
      $trendsBoston[] = $trend['name'] ;
    }				

    /** Get the trends in LA **/
    $getfield = '?id=2442047' ;
    $trendsJSON = json_decode( $twitter->setGetfield($getfield)
                               ->buildOauth($url, $requestMethod)
                                ->performRequest(),true);

    /** Populate trendsLA with the trends of LA **/
    foreach ( $trendsJSON[0]['trends'] as $trend) {
      $trendsLA[] = $trend['name'] ;
    }

    /** Get the trends in Atlanta **/
    $getfield = '?id=2357024' ;
    $trendsJSON = json_decode( $twitter->setGetfield($getfield)
                               ->buildOauth($url, $requestMethod)
                                ->performRequest(),true);

    /** Populate trendsLA with the trends of LA **/
    foreach ( $trendsJSON[0]['trends'] as $trend) {
      $trendsAtlanta[] = $trend['name'] ;
    }

    echo '<table id="trendTable">';
    echo '<tr><td>Top Ten Trends of Boston</tr></td>';
    foreach ( $trendsBoston as $trend) {
      echo '<tr class="trendRow">';
      echo '<td><p class="trendName">' . $trend . '</p></td></tr>' ;
    }
    echo '</table>';

    echo '<table id="trendTable">';
    echo '<tr><td>Top Ten Trends of LA</tr></td>';
    foreach ( $trendsLA as $trend) {
      echo '<tr class="trendRow">';
      echo '<td><p class="trendName">' . $trend . '</p></td></tr>' ;
    }
    echo '</table>';

    echo '<table id="trendTable">';
    echo '<tr><td>Top Ten Trends of Atlanta</tr></td>';
    foreach ( $trendsAtlanta as $trend) {
      echo '<tr class="trendRow">';
      echo '<td><p class="trendName">' . $trend . '</p></td></tr>' ;
    }
    echo '</table>';

    ?>

    <img src="media/brd-transp.gif" id="brLogo"/>

  </body>
</html>

