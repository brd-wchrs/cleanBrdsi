<html>
  <head>
    <!-- 
      File: twittertrends.php
      Author: Alan Estrada
    
      Comments because HEINES MAKES ME FEEL BAD 
      
      Literally for just messing around with the basic twitter API.
      This one is for grabbing the closest trending information.
      
      Using someone else's class TwitterAPIExchange.php for OAuth.
      -->

    <title>Testing Twitter</title>

    <!-- Oh, hi, this links to a very lazily done css file -->
    <link rel="stylesheet" type="text/css" href="twitter.css">

    <!-- The following resource is adopted and adapted from http://api.mygeoposition.com/geopicker/ -->

    <script src="http://api.mygeoposition.com/api/geopicker/api.js" type="text/javascript"></script>
    <script type="text/javascript">
        function lookupGeoData() {
            myGeoPositionGeoPicker({
                startAddress     : 'White House, Washington',
                zoomLevel        : '20',
	        returnFieldMap   : {
                                     'geoposition1a' : '<LAT>',
                                     'geoposition1b' : '<LNG>',
                                   }
            });
        }
    </script>
  </head>
  <body>

    <!-- The form to submit the lat, long, and radius to search trends -->
    <form name="form1" method="POST" action="twittertrends.php">
      <table>
	<tr>
          <td>Latitude:</td>
          <td><input type="text" id="geoposition1a" value="32.09" name="latitude"></td>
        </tr>
        <tr>
          <td>Longitude:</td>
          <td><input type="text" id="geoposition12" value="-70.012" name="longitude"></td>
	</tr>
	<tr>
	  <td>Radius (mi):</td>
	  <td><input type="text" value="1000" name="radius"></td>
	</tr>
	<tr>
	  <td><input type="submit" name="querySubmit" value="Search"></td>
          <td><button type="button" onclick="lookupGeoData();">Open GeoPicker</button></td>
	</tr>
      </table>
    </form>

    <?php

    require_once('TwitterAPIExchange.php');

    /** Set access tokens here - see: https://dev.twitter.com/apps/ **/
    $settings = array(
      'oauth_access_token' => "2326287732-4iSDtAQB7nhc7EsvxFNUgOBg5LJpdqYvbQZEP0C",
      'oauth_access_token_secret' => "pBJ4aPRnfkEBBAqOUK9k8GDf7LHJBD2xPV4w0tmphYUhf",
      'consumer_key' => "y7ojWz9W1EAqeFyK5BsHfA",
      'consumer_secret' => "atcvh1uWEdNNZ2hnlUrcrFuE1yTqlnRHDpToVxik"
    );

    /** So yeah, this part grabs the stuff from the textboxes **/
    /** $query = $_POST['query'] ; **/
    $latitude = $_POST['latitude'] ;
    $longitude = $_POST['longitude'] ;
    $radius = $_POST['radius'] ;


    /* This creates the url that requests the place with the closest
       trending information to the GPS coordinates. 
    */
    $url = 'https://api.twitter.com/1.1/trends/closest.json';
    $getfield = '?lat=' . $latitude . '&long=' . $longitude ; 

    $requestMethod = 'GET';
    $twitter = new TwitterAPIExchange($settings);

    $closestJSON = json_decode( $twitter->setGetfield($getfield)
                                ->buildOauth($url, $requestMethod)
                                ->performRequest(),true);     

    /* Echos the country and the WoeID */
    echo "It seems like the closest place with trending information is at the" .
         " WOEid: " . $closestJSON[0]['woeid'] . " which is in the country " .
         $closestJSON[0]['country'];

    /* $closestJSON[0]['country'] . ': ' .  $closestJSON[0]['woeid'] . '<br>'; */

    /** Now we create the URL that requests the trends of the WOEid **/
    $url = 'https://api.twitter.com/1.1/trends/place.json';
    $getfield = '?id=' . $closestJSON[0]['woeid'] ;

    echo "<p>Here are the top trends of the area and the top five tweets of each:</p>" ;

    $trendsJSON = json_decode( $twitter->setGetfield($getfield)
                               ->buildOauth($url, $requestMethod)
                                ->performRequest(),true);


    echo '<table id="trendTable">';
    foreach ( $trendsJSON[0]['trends'] as $trend) {
      echo '<tr class="trendRow">';
      echo '<td><p class="trendName">' . $trend['name'] . '</p>' ;

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
      echo '</ul>';
      echo '</td></tr>';
    } 
    echo '</table>';

    ?>

    <img src="media/brd-transp.gif" id="brLogo"/>

  </body>
</html>

