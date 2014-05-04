<?php

/**
 * Alan Estrada
 * trendLook.php
 * 
 * This script accepts three uri arguments, queries twitter, and generates 
 * html.
 * 
 * 
 */

    // code to Get the tweets

    /* (This is from Alan's code) */

    require_once('TwitterAPIExchange.php');

    /** Set access tokens here - see: https://dev.twitter.com/apps/ **/
    $settings = array(
      'oauth_access_token'        => "2326287732-4iSDtAQB7nhc7EsvxFNUgOBg5LJpdqYvbQZEP0C",
      'oauth_access_token_secret' => "pBJ4aPRnfkEBBAqOUK9k8GDf7LHJBD2xPV4w0tmphYUhf",
      'consumer_key'        => "y7ojWz9W1EAqeFyK5BsHfA",
      'consumer_secret'     => "atcvh1uWEdNNZ2hnlUrcrFuE1yTqlnRHDpToVxik"
    );


    /** Now we create the URL that requests the trends of the WOEid **/
    $url = 'https://api.twitter.com/1.1/trends/closest.json';

    // make a twitter object from the api
    $twitter = new TwitterAPIExchange($settings);

    /*** Get params from the script ***/
    $latitude   = isset( $_GET['lat'] ) ? $_GET['lat'] : "42.35"  ;
    $longitude  = isset( $_GET['lng'] ) ? $_GET['lng'] : "-71.06" ;
    $radius     = isset( $_GET['rad'] ) ? $_GET['rad'] : "500"    ;

    $getfield = '?lat=' . $latitude . '&long=' . $longitude ;
    $requestMethod = 'GET';
 
    echo "<!-- using $getfield -->";

    $closestJSON = json_decode( $twitter->setGetfield($getfield)
                                ->buildOauth($url, $requestMethod)
                                ->performRequest(),true);
 
    //var_dump( $closestJSON ) ;
    
    $url = 'https://api.twitter.com/1.1/trends/place.json';
    $getfield = '?id=' . $closestJSON[0]['woeid'] ;

    $trendsJSON = json_decode( $twitter->setGetfield($getfield)
                                 ->buildOauth($url, $requestMethod)
                                 ->performRequest(),true);



// NRS: 4/22/2014: moved this up so that the paragraph would be inside it
  
  	echo '<div class="center">';

  
    /* this is where the paragraph was */

    /* this is where <div class=center> was */

    echo '<div class="statsTable">';
    echo '<table id="trendTable" class="flat-table flat-table-2">';
    echo '<tr><td>';
    echo "Trends for your region";
    echo '</td></tr>';
    
    foreach ( $trendsJSON[0]['trends'] as $trend) {
      echo '<tr class="trendRow">';
      echo '<td><p class="trendName">' . $trend['name'] . '</p>' ;
	  /*
      $noSpaceTrend = str_replace( ' ', '%20', $trend['name'] ) ;

      $url = 'https://api.twitter.com/1.1/search/tweets.json';
      $getfield = '?q=' . $noSpaceTrend . '&geocode=' . $latitude . ',' .
                  $longitude . ',' . $radius . 'mi' . '&count=5' ;

      $tweetsJSON = json_decode( $twitter->setGetfield($getfield)
                                 ->buildOauth($url, $requestMethod)
                                 ->performRequest(),true);

      echo '<ul>';
      foreach($tweetsJSON['statuses'] as $status) {
        echo '<li><table><tr>';
        echo '<td><img src="' . $status['user']['profile_image_url'] . '"/></td>';
        echo '<td>' . $status['user']['name'] . '</td>';
        echo '<td>' . $status['text'] . '</td>';
        echo '</tr></table></li>';
      }
      echo '</ul>';*/
      echo '</td></tr>';
    } 
    echo '</table>';
    echo '</div>';
    
    // NRS: 4/22/2014: adding paragraph tags to this
    /*echo "<p>It seems like the closest place with trending information is at the" .
         " <span class='highlight'>WOEid: " . $closestJSON[0]['woeid'] . "</span> which is in the country <span class='highlight'>" .
         $closestJSON[0]['country'] . "</span></p>";*/
    
    echo "<p>The closest place with trending information is in the country <span class='highlight'>" ,
         $closestJSON[0]['country'] , "</span></p>";
    
    echo '</div>';
    

	/** old stuff
    echo '<table>';
    echo '<tr><td></td><td>User</td><td>Tweet</td></tr>';
    foreach($trendsJSON[0]['trends'] as $trends) {
      echo '<tr><td><img src="' . $status['user']['profile_image_url'] . '"/></td>';
      echo '<td>' . $status['user']['name'] . '</td>';
      echo '<td>' . $status['text'] . '</td></tr>';
    }
    echo '</table>';
    echo '</div>';
    echo '</div>';
	**/

?>