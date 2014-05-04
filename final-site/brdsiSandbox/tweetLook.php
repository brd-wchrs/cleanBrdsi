<?php

    // code to Get the tweets

    /* This is from Alan's code! */

    require_once('TwitterAPIExchange.php');

    /** Set access tokens here - see: https://dev.twitter.com/apps/ **/
    $settings = array(
      'oauth_access_token'        => "2326287732-4iSDtAQB7nhc7EsvxFNUgOBg5LJpdqYvbQZEP0C",
      'oauth_access_token_secret' => "pBJ4aPRnfkEBBAqOUK9k8GDf7LHJBD2xPV4w0tmphYUhf",
      'consumer_key'        => "y7ojWz9W1EAqeFyK5BsHfA",
      'consumer_secret'     => "atcvh1uWEdNNZ2hnlUrcrFuE1yTqlnRHDpToVxik"
    );


    // todo: add location choosing.


    /** Now we create the URL that requests the trends of the WOEid **/
    $url = 'https://api.twitter.com/1.1/search/tweets.json';

    // make a twitter object from the api
    $twitter = new TwitterAPIExchange($settings);

    /*** Get params from the script ***/
    $latitude   = isset( $_GET['lat'] ) ? $_GET['lat'] : "42.35"  ;
    $longitude  = isset( $_GET['lng'] ) ? $_GET['lng'] : "-71.06" ;
    $radius     = isset( $_GET['rad'] ) ? $_GET['rad'] : "500"    ;
    $searchStr  = isset( $_GET['q'] )   ? $_GET['q']   : "pokemon";

    $searchStr  =  str_replace(" ", "", $searchStr);
    $searchStr  =  str_replace("+", "", $searchStr);

    if(empty($searchStr)){print "<input type='hidden' value='ERROR: NO q in URI' >";}

    $getfield = "?q=$searchStr&geocode=$latitude,$longitude,$radius".'mi&count=15' ;

 
    $resultJSON = $twitter->setGetfield($getfield)
                          ->buildOauth($url, 'GET')
                          ->performRequest();
    
    
    // NRS: April 9: have this script just return the json
    // echo $resultJSON;
    
    // more alan code

     
      $tweetsJSON = json_decode( $resultJSON, true );
	  
	  echo '<div class="center">';
	  echo '<div class="statsTable">';
      echo '<table>';
      echo '<tr><td></td><td>User</td><td>Tweet</td></tr>';
      foreach($tweetsJSON['statuses'] as $status) {
        echo '<tr><td><img src="' . $status['user']['profile_image_url'] . '"/></td>';
        echo '<td>' . $status['user']['name'] . '</td>';
        echo '<td>' . $status['text'] . '</td></tr>';
      }
      echo '</table>';
      echo '</div>';
      echo '</div>';
