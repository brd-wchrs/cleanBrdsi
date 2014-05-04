<html>
  <head>
    <!-- 
      File: timeline.php
      Author: Alan Estrada
    
      Comments because HEINES MAKES ME FEEL BAD 
      
      Literally for just messing around with the basic twitter API.
      
      Using someone else's class TwitterAPIExchange.php for OAuth.

      This also needs to unirest-php and ChartJS for this to work.
      -->

    <title>Parsing Timelines</title>
    <script src='libraries/Chart.js/Chart.js'></script>
  </head>
  <body>

    <form name="form1" method="POST" action="timeline.php">
      <table>
	<tr>
	  <td>Screen Name:</td>
	  <td><input type="text" name="screenname"></td>
	</tr>
	<tr>
      </table>
    </form>

    <?php

    require_once('wordCloudMaker.php') ;

    require_once('TwitterAPIExchange.php');

    /** Set access tokens here - see: https://dev.twitter.com/apps/ **/
    $settings = array(
      'oauth_access_token' => "2326287732-DY4QCFFLLL8cZkNKEhbdthu84567o4AjhJjQFGE",
      'oauth_access_token_secret' => "naCMorFD9wCUYF3PC6rh47vuikDmlfRYuprKJsNBofKQN",
      'consumer_key' => "pIMhuLu8PLs7IZYYFbZMbQ",
      'consumer_secret' => "1h3uAUlnwwkQfSK0mKpLwMApDd4pEmxffBaSjTmO9g"
    );

    /** So yeah, this part grabs the stuff from the textboxes */
    $query = $_POST['screenname'] ;

    $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
    $getfield = '?screen_name=' . $query . '&count=200&contributor_details=true'; 

    /** Default code, copied from source. **/
    $requestMethod = 'GET';
    $twitter = new TwitterAPIExchange($settings);
    
    $json = json_decode( $twitter->setGetfield($getfield)
                         ->buildOauth($url, $requestMethod)
                         ->performRequest(),true);     


    /** Variables to contain data **/
    $tags = array() ;             // contains the hashtags use. example:
                                  // $tags["kony2012"] => 12
                                  

    $usersMentioned = array() ;   // contains the users mentioned. example:
                                  // $usersMentioned["asianfriendbro"] => 5

    $dates = array() ;            // contains the days in which there were tweets

    $daysOfWeek = array("Sun" => 0, "Mon" => 0,"Tue" => 0,"Wed" => 0,"Thu" => 0,"Fri" => 0,"Sat" => 0,) ;       

                                  // contains the number of tweets per day of the week
                                  // indexes are first three letters of day
                                  // example: $daysOfWeek["Thu"] => 5

    $favoritedTweets = array() ;  // contains the indices of top favorited tweets
    $topFavoritedTweets = array() ;
                                  // multi-dimensional array containing the top five
                                  // favorited tweets. Examples:
                                  // $topFavoritedTweets[i]["text"] => "hello" 
                                  // $topFavoritedTweets[i]["created_at"] => "January 25, 2012, 5:32pm"
                                  // $topFavoritedTweets[i]["favorite_count"] => 12121

    $beginDate = '' ;             // contains the date of the earliest tweet received
    $endDate = '' ;               // contains the date of the latest tweet received
    $retweets = 0 ;               // contains how many retweets
    $replies = 0 ;                // contains how many replies
    $userMentions = 0 ;           // contains how many times a user was mentioned
    $hashtags = 0 ;               // contains how many times a hashtag was used

    $totalTweets = count($json) ; // number of tweets received

    // Variables for the live charts
    $tagsTextCharts = array() ;
    $tagsUsageCharts = array() ;

    $usersNameCharts = array() ;
    $usersUsageCharts = array() ;    

    // colors for the pie chart for Sun, Mon, Tue, ..., Sat
    $daysColors = array("#D97041", "#C7604C", "#21323D", "#9D9B7F", "#7D4F6D", "#584A5E", "#69D2E7") ;
    $daysData = array() ;

    /** Iterate through the json of tweets to analyze data **/
    $count = 0 ;
    foreach( $json as $tweet ) {
           
      /** Finds the date of the first and last tweets received **/
      if ( $tweet === reset($json) ) {
        $beginDate = $tweet["created_at"] ;
      }

      if ( $tweet === end($json) ) {
        $endDate = $tweet["created_at"] ;
      }
    
      /** Finds the the five tweets with the most favorites **/
      if ( count($favoritedTweets) < 5 ) {
         $favoritedTweets[] = $count ;
      }
      elseif ( array_key_exists( "favorite_count" , $tweet) 
               && !is_null( $tweet["favorite_count"] )) {
	foreach ( $favoritedTweets as &$tweetNum ) {
	  if ( $tweet["favorite_count"] > $json[$tweetNum]["favorite_count"] ) {
            $tweetNum = $count ;
            break ;
          }
        }
      }
				     
      /** Populates tags with the hashtags and how many times used **/
      foreach ( $tweet["entities"]["hashtags"] as $hashtag ) {
        $tags[$hashtag["text"]]++ ;
        $hashtags++ ;
       }
				     
      /** Fills usersMentioned with the users mentioned and how many times **/
      foreach ( $tweet["entities"]["user_mentions"] as $user ) {
        $usersMentioned[$user["screen_name"]]++ ;
        $userMentions++ ;
      }

      /** Finds the day of the week the tweet was posted **/
      $day = date('D',strtotime($tweet["created_at"])) ;
      $daysOfWeek[$day]++;

      /** Increments each date tweeted **/
      $dates[$tweet["created_at"]]++;

      /** Check if this is a retweet **/
      if ( array_key_exists( "retweeted_status", $tweet ) ) {
        $retweets++ ;
      }

      /** Check if this is a reply **/
      if ( !is_null( $tweet["in_reply_to_user_id"] )) {
        $replies++ ;
      }

      $count++ ;
    }

    /****** This is where we start analyzing the stats ******/

    /** Sort the arrays to get top ten values of each **/
    arsort($tags) ;
    arsort($usersMentioned);

    /** Populate the arrays needed to build a live chart for tags **/
    $count = 0 ;
    foreach ( $tags as $tag => $value ) {
      if ( $count > 10 || $value == 0 ) {
        break ; 
      }

      $tagsTextCharts[] = $tag ;
      $tagsUsageCharts[] = $value ;
      $count++ ;
    }

    /** Populate the arrays needed to build a live chart for the user mentions **/
    $count = 0 ;
    foreach ( $usersMentioned as $user => $value ) {
      if ( $count > 10 || $value == 0 ) {
        break ;
      }

      $usersNameCharts[] = $user ;
      $usersUsageCharts[] = $value ;
      $count++ ;
    }

    /** Populate the arrays needed to build a live chart for the user mentions **/
    $count = 0 ;
    foreach (  $daysOfWeek as $day => $value ) {
      array_push( $daysData, array( "value" => $value, "color" => $daysColors[$count] ));
      $count++;
    }

    /** Counts percentage of total tweets are retweets **/
    $retweets = ($retweets / $totalTweets) * 100 ;
    $retweets = number_format( $retweets, 1 ) ;

    /** Counts percentage of total tweets are replies **/
    $replies = ($replies / $totalTweets) * 100 ;
    $replies = number_format( $replies, 1 ) ;

    /** Format the dates **/
    $f_beginDate = date('F j, Y, g:ia', strtotime($beginDate)) ;
    $f_endDate = date('F j, Y, g:ia', strtotime($endDate)) ;

    /** Find the average number of tweets per day **/
    $date1 = date('Y-m-d', strtotime($beginDate));
    $date2 = date('Y-m-d', strtotime($endDate));

    $diff = abs(strtotime($date2) - strtotime($date1)) ;
    $totalDays = $diff / (60 * 60 * 24) ;

    $tweetsPerDay = number_format($totalTweets / $totalDays, 1) ;

    /** Find the percentage of tweets posted per day of the week **/
    foreach ( $daysOfWeek as &$day ) {
      $day = number_format( ($day / $totalTweets ) * 100, 1) ; 
    }

    /** Store the top five favorited tweets in topFavoritedTweets **/
    foreach ( $favoritedTweets as $tweetNum ) {
      $date = $json[$tweetNum]["created_at"] ;
      $fDate = date('F j, Y, g:ia', strtotime($date)) ;
      $topFavoritedTweets[] = array( "text" => $json[$tweetNum]["text"],
                                     "created_at" => $fDate,
	                             "favorite_count" => $json[$tweetNum]["favorite_count"] ) ;
    } 

    /** Generate a word cloud **/
    $wordCloudImage = generateWordCloud( $json, 800, 800 ) ;

    /****** This is where we stop analyzing the stats ******/

    /****** This is where we begin to display our analysis ******/

    //echo "Judging from " . $query . "'s tweets from " . $f_beginDate . " to " . $f_endDate . "..." ;
    
    echo "In these " . number_format($totalDays, 0) . " days ..." ;

    echo "<br><br>" ;
    echo "Initial statistics: <br>" ;
    echo $query . "makes " . $tweetsPerDay . " tweets per day.<br>" ;
    echo $retweets . "% of " . $query . "'s tweets are retweets.<br>" ;
    echo $replies . "% of " . $query . "'s tweets are replies.<br>" ;
    echo $query . " used " . $hashtags . " hashtags and mentioned " .
         $userMentions . " users.<br>";
    echo "<br>" ;

    /** Display the top ten (or less) hashtags used **/
    echo '<table>';
    echo '<tr><td>Top Hashtags</td><td>Usage</td></tr>';
    $count = 0;
    foreach( $tags as $tag => $value ) {      
      if ( $count >= 10 || $value == 0 ) {
        break;
      }

      $count++;
      echo '<tr><td>' . $count . '. ' . $tag . '</td>' .
	   '<td>' . $value . '</td></tr>'; 
     
    }    
    echo '</table>';
    echo '<br>' ;

    /** Bar chart for hashtags used **/
    echo '<canvas id="tagsChart" width="600" height="400"></canvas>' ;

    echo '<br>' ;

    /** Display the top ten (or less) users mentioned **/
    echo '<table>';
    echo '<tr><td>Users Mentioned:</td><td>Mentions:</td></tr>';
    $count = 0;
    foreach( $usersMentioned as $user => $value ) {
      $count++;
      echo '<tr><td>' . $count . '. ' . $user . '</td>' .
           '<td>' . $value . '</td></tr>';
      if ( $count >= 10) {
        break;
      }
    }
    echo '</table>';
    echo '<br>' ;

    /** Bar chart for userNames mentioned **/
    echo '<canvas id="usersChart" width="600" height="400"></canvas>' ;
    echo '<br>' ;


    /** Display breakdown of percentage of tweets per weekday **/
    echo '<table>';

    echo '<tr><td>Day of the Week</td>' . 
	 '<td> Percentage of Tweets Posted</td></tr>';

    if ( $daysOfWeek['Sun'] > 0 ) {
      echo '<tr><td>Sunday</td><td>' . $daysOfWeek['Sun'] .
  	   '%</td></tr>' ;
    }

    if ( $daysOfWeek['Mon'] > 0 ) {
      echo '<tr><td>Monday</td><td>' . $daysOfWeek['Mon'] .
	   '%</td></tr>' ;
    }

    if ( $daysOfWeek['Tue'] > 0 ) {
      echo '<tr><td>Tuesday</td><td>' . $daysOfWeek['Tue'] .
  	   '%</td></tr>' ;
    }

    if ( $daysOfWeek['Wed'] > 0 ) {
      echo '<tr><td>Wednesday</td><td>' . $daysOfWeek['Wed'] .
      	   '%</td></tr>' ;
    }

    if ( $daysOfWeek['Thu'] > 0 ) {
    echo '<tr><td>Thursday</td><td>' . $daysOfWeek['Thu'] .
	 '%</td></tr>' ;
    }

    if ( $daysOfWeek['Fri'] > 0 ) {
      echo '<tr><td>Friday</td><td>' . $daysOfWeek['Fri'] .
	   '%</td></tr>' ;
    }

    if ( $daysOfWeek['Sat'] > 0 ) {
      echo '<tr><td>Saturday</td><td>' . $daysOfWeek['Sat'] .
	   '%</td></tr>' ;
    }

    echo '</table>';
    echo '<br>' ;

    /** Pie chart for days tweeted **/
    echo '<canvas id="daysChart" width="600" height="400"></canvas>' ;
    echo '<br>' ;

    echo '<table>';
    echo '<tr><td>Highest Favorited Tweets</td>' .
         '<td>Date</td><td>Number of Favorites</td></tr>' ;

    foreach ( $topFavoritedTweets as $tweet ) {
      if ( $tweet["favorite_count"] > 0 ) {
        echo '<tr><td>' . $tweet["text"] . '</td>' ;
        echo '<td>' . $tweet["created_at"] . '</td>' ;
        echo '<td>' . $tweet["favorite_count"] . '</td></tr>' ;
      }
    }

    echo '</table>' ;

    echo '<br>Here is the word cloud generated from your tweets:<br>' ;
    echo '<img src=' . $wordCloudImage . '>' ;

    /****** This is where we stop displaying our analysis ******/

    ?>


    <script type="text/javascript">
      // This part creates the live chart for the hashtags
      var tagsText = <?php echo json_encode($tagsTextCharts); ?>;
      var tagsUsage = <?php echo json_encode($tagsUsageCharts); ?>;

      tagsStep = Math.ceil(tagsUsage[0] / 10 ) ;

      var tagsData = {
        labels : tagsText,
        datasets : [
          {
            fillColor : "#48A497",
            strokeColor : "#48A4D1",
            data : tagsUsage
          }
        ] 
      } ;

      var tagsChart = document.getElementById("tagsChart").getContext("2d");
      var tagsOption = {
      scaleOverlay : true,

        //Boolean - If we want to override with a hard coded scale
        scaleOverride : true,
       
        //** Required if scaleOverride is true **
        //Number - The number of steps in a hard coded scale
        scaleSteps : 10,
        //Number - The value jump in the hard coded scale
        scaleStepWidth : tagsStep,
        //Number - The scale starting value
        scaleStartValue : 0,
      barDatasetSpacing : 10,
      };

      new Chart(tagsChart).Bar(tagsData, tagsOption);

      // This part creates the live chart for the user mentions
      var usersNames = <?php echo json_encode($usersNameCharts); ?>;
      var usersUsage = <?php echo json_encode($usersUsageCharts); ?>;

      usersStep = Math.ceil(usersUsage[0] / 10 ) ;

      var usersData = {
        labels : usersNames,
        datasets : [
          {
            fillColor : "#48A497",
            strokeColor : "#48A4D1",
            data : usersUsage
          }
        ]
      } ;

      var usersChart = document.getElementById("usersChart").getContext("2d");
      var usersOption = {
      scaleOverlay : true,

        //Boolean - If we want to override with a hard coded scale
        scaleOverride : true,

        //** Required if scaleOverride is true **
        //Number - The number of steps in a hard coded scale
        scaleSteps : 10,
        //Number - The value jump in the hard coded scale
        scaleStepWidth : tagsStep,
        //Number - The scale starting value
        scaleStartValue : 0,
        barDatasetSpacing : 10,
      };

      new Chart(usersChart).Bar(usersData, usersOption);

      // This part creates a live chart for the days tweeted
      var daysData = <?php echo json_encode($daysData); ?>;
      var daysChart = document.getElementById("daysChart").getContext("2d");
      
      new Chart(daysChart).PolarArea(daysData) ;
    </script>

  </body>
</html>
