<?php
// alan wrote this 

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
    
    
?>
