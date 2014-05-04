<html>
  <head>
    <!-- 
      File: twittertest.php
      Author: Alan Estrada
    
      Comments because HEINES MAKES ME FEEL BAD 
      
      Literally for just messing around with the basic twitter API.
      
      Using someone else's class TwitterAPIExchange.php for OAuth.
      -->

    <title>Testing Twitter</title>

    <!-- The following resource is adopted and adapted from http://api.mygeoposition.com/geopicker/ -->

    <script src="http://api.mygeoposition.com/api/geopicker/api.js" type="text/javascript"></script>
    <script type="text/javascript">
        function lookupGeoData() {            
            myGeoPositionGeoPicker({
                startAddress     : 'White House, Washington',
                returnFieldMap   : {
                                     'geoposition1a' : '<LAT>',
                                     'geoposition1b' : '<LNG>',
                                   }
            });
        }
    </script>


  </head>
  <body>

    <form name="form1" method="POST" action="twittertest.php">
      <table>
	<tr>
	  <td>Query:</td>
	  <td><input type="text" value="#asianproblems" name="query"></td>
	</tr>
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
    $query = $_POST['query'] ;
    $latitude = $_POST['latitude'] ;
    $longitude = $_POST['longitude'] ;
    $radius = $_POST['radius'] ;
    
    $url = 'https://api.twitter.com/1.1/search/tweets.json';
    $getfield = '?q=' . $query . '&geocode=' . $latitude . ',' .
                $longitude . ',' . $radius . 'mi' ;

    $requestMethod = 'GET';
    $twitter = new TwitterAPIExchange($settings);

    $json = json_decode( $twitter->setGetfield($getfield)
                         ->buildOauth($url, $requestMethod)
                         ->performRequest(),true);     

    echo '<table>';

    foreach($json['statuses'] as $status) {
      echo '<tr>';
      echo '<td><img src="' . $status['user']['profile_image_url'] . '"/></td>';
      echo '<td>' . $status['user']['name'] . '</td>';
      echo '<td>' . $status['text'] . '</td>';
      echo '</tr>';
    }

    echo '</table>';
    ?>
  </body>
</html>

