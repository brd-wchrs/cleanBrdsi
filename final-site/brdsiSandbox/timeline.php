<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

<!--(C) Bri Gainley, Alan Estrada, Nick St. Pierre, 2014 -->
<!-- With code from http://www.alessioatzeni.com/blog/signin-dropdown-box-like-twitter-with-jquery/ -->
<!-- for dropdown login menu -->

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>brdsi - A Twitter Analysis Tool</title>
        <meta name="description" content="Twitter trend analysis, delivered right to your screen">
        <meta name="viewport" content="width=device-width">
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <!-- build:css styles/vendor.css -->
        <!-- bower:css -->

        <!-- endbower -->
        <!-- endbuild -->
        <!-- build:css(.tmp) styles/main.css -->
        <link rel="stylesheet" href="styles/reset.css">
        <link rel="stylesheet" href="styles/main.css">
        <link rel="stylesheet" href="styles/statsTable.css">
        <link rel="stylesheet" href="styles/timeline.css">
        <link rel="stylesheet" href="styles/col.css">
        <link rel="stylesheet" href="styles/tabs.css">
        <link rel="icon" type="image/png" href="images/favicon.ico">

        <!-- Google Fonts -->
        <link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700' rel='stylesheet' type='text/css'>

        <script src="scripts/Chart.js" type="text/javascript"></script>

        <!-- jquery -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    </head>
    <body>

      
      
		<div class="container">
      <div id="top-navbar">
				<a href="bees.php"><img src="images/brdsiLogo.png"></a>
				<ul>
				  <li><a href="timeline.php">Timeline Analysis</a></li>
				  <li><a href="maps.php">Region Graph</a></li>
				  <li><a href="friendTrends.php">Friend Trends</a></li>
				</ul>
      </div>

			<div id="content">
			<div class>
		    <form name="screenName" method="POST" action="timeline.php">
          <table>
            <tr>
              <td><h2>Please enter a Twitter user you'd like to analyze:</h2></td>
              <td><input id="userTextbox" type="text" name="screenname" value="<?php echo isset($_POST['screenname']) ? $_POST['screenname'] : "" ; ?>"></td>
            </tr>
            <tr>

              <?php
              
                if( !isset($_POST['screenname'])) {
                  echo '<td>(If you don\'t know where to start, try searching '. 
                  '"<a href="#" onclick="document.getElementById(\'userTextbox\').value=\'KatyPerry\';document.screenName.submit();">'  .
                  '<span class="highlight">KatyPerry</span></a>")</td>' ;
                  }
              ?>


            </tr>
          </table>
		    </form>
		    </div>

		    <?php

        /** profile code nstpierre **/
        $lastStamp=time();
        function startProfileTimer()
        {
          global $lastStamp;
          $lastStamp = time();
        }

        function profileEventCompleted($eventName)
        {
            global $lastStamp;

            $secondsElapsed = time() - $lastStamp ;
            $lastStamp = time();

            echo "<!-- Event '$eventName' took $secondsElapsed seconds -->\n";
        }

        /**********************************/


		    require_once('wordCloudMaker.php') ;
		    require_once('TwitterAPIExchange.php');

		    /** Set access tokens here - see: https://dev.twitter.com/apps/ **/
		    $settings = array(
		      'oauth_access_token'        => "2326287732-DY4QCFFLLL8cZkNKEhbdthu84567o4AjhJjQFGE",
		      'oauth_access_token_secret' => "naCMorFD9wCUYF3PC6rh47vuikDmlfRYuprKJsNBofKQN",
		      'consumer_key'              => "pIMhuLu8PLs7IZYYFbZMbQ",
		      'consumer_secret'           => "1h3uAUlnwwkQfSK0mKpLwMApDd4pEmxffBaSjTmO9g"
		    );


        startProfileTimer();

		    /** So yeah, this part grabs the stuff from the textboxes */
		    
        if( isset($_POST['screenname'])){
          
        $query = $_POST['screenname'] ;
        
        

		    $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
		    $getfield = '?screen_name=' . $query . '&count=200&contributor_details=true';

		    /** Default code, copied from source. **/
		    $requestMethod = 'GET';
		    $twitter = new TwitterAPIExchange($settings);

		    $json = json_decode( $twitter->setGetfield($getfield)
		                         ->buildOauth($url, $requestMethod)
		                         ->performRequest(),true);

        if ( is_null( $json ) || array_key_exists( "errors", $json )) {
          echo "<p class='center'>" ;
          echo "<span class='highlight'>"  . $_POST['screenname'] . "</span>" ;
          echo "'s timeline is either private or does not exist.</p>" ;
        }
        else {
        profileEventCompleted("get user_timeline.json and json_decode");

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


        startProfileTimer();
        // analysis loop #1
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
		      if ( count($favoritedTweets) <= 5 ) {
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

        profileEventCompleted("Analysis loop #1");

		    /****** This is where we start analyzing the stats ******/

		    /** Sort the arrays to get top ten values of each **/
		    arsort($tags) ;
		    arsort($usersMentioned);

        profileEventCompleted("two arsort calls");

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

        profileEventCompleted("array populations");

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
                                "favorite_count" => $json[$tweetNum]["favorite_count"],
                                "username" => $json[$tweetNum]["user"]["name"],
                                "profile_img" => $json[$tweetNum]["user"]["profile_image_url"],
                                "user_id" => $json[$tweetNum]["user"]["id_str"],
                                "tweet_id" => $json[$tweetNum]["id_str"]
                              ) ;
		    }

        profileEventCompleted("tweet analysis");

        function generateRandomString($length = 10) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }
            return $randomString;
        }
        
        $wordcloudJSONFileName = "tempfiles/" . generateRandomString(12).".sjs";

        // thanks http://stackoverflow.com/questions/4356289/php-random-string-generator
        echo"<!-- function declaration -->";



        if ( file_put_contents( $wordcloudJSONFileName, serialize($json) ) === FALSE){
          
          echo"<!-- yo file io failed -->";
        }


//        echo "<input type='hidden' id='wordcloudJSON' value='".."' >";

		    /****** This is where we stop analyzing the stats ******/
        profileEventCompleted("generated wordcloud");
		    /****** This is where we begin to display our analysis ******/

		    //echo "Judging from " . $query . "'s tweets from " . $f_beginDate . " to " . $f_endDate . "..." ;
		    echo "<div id='tabBox'>" ;
		    echo "<ul class='tabs'>" ;
		    
		    echo "<li>" ;
		    echo "<input type='radio' checked name='tabs' id='tab1'>" ;
		    echo "<label for='tab1'>General Stats</label>" ;
		    echo "<div id='tab-content1' class='tab-content'>" ;
		    echo "<div class='left' id='generalStats'>" ;
		    echo "In the past <span class='highlight'>" . number_format($totalDays, 0) . "</span> days ..." ;

		    echo "<br><br>" ;
//		    echo "Initial statistics: <br>" ;
		    echo $query . " made <span class='highlight'>" . $tweetsPerDay . "</span> tweets per day.<br>" ;
		    echo "<span class='highlight'>" . $retweets . "%</span> of " . $query . "'s tweets are retweets.<br>" ;
		    echo "<span class='highlight'>" . $replies . "%</span> of " . $query . "'s tweets are replies.<br>" ;
		    echo $query . " used <span class='highlight'>" . $hashtags . " </span>hashtags and mentioned <span class='highlight'>" .
		         $userMentions . "</span> users.<br>";
		    echo "</div>" ;
		    
		    echo "<div class='right' id='faveTweets'>" ;
		    echo '<table class="flat-table flat-table-2">';
		    echo '<tr><td>Highest Favorited Tweets</td>' .
		             '<td>Date</td><td>Number of Favorites</td></tr>' ;

        $count = 0;
		    foreach ( $topFavoritedTweets as $tweet ) {
          if ( $count == 5 ) {
              break ;
          }
		      if ( $tweet["favorite_count"] > 0 ) {
            $tweetLink = 'https://twitter.com/' . $tweet["user_id"] . '/statuses/' . $tweet["tweet_id"] ;
            //echo '<tr class="favoritedTweetRow" onclick="window.document.location=\'' . $tweetLink . '\'">' ;
            echo '<tr class="favoritedTweetRow" onclick="window.open(\'' . $tweetLink . '\', \'_blank\');">' ;
            echo '<td>' . $tweet["text"] . '</td>' ;
	          echo '<td>' . $tweet["created_at"] . '</td>' ;
		        echo '<td>' . $tweet["favorite_count"] . '</td></tr>' ;
		      }
          $count++;
		    }

		    echo '</table>' ;
		    echo "</div>" ; /* end right table div */
		    echo "</div>" ; /* end tab content div */
		    echo "</li>" ; /* first tab end */

			echo "<li>" ;
			echo "<input type='radio' name='tabs' id='tab2'>" ;
			echo "<label for='tab2'> Top Hashtags </label>" ;
			echo "<div id='tab-content2' class='tab-content'>" ;
			echo "<div class='left' id='topHashtags'>" ;
		    /** Display the top ten (or fewer) hashtags used **/
		    echo '<table class="flat-table flat-table-2">';
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
		    echo "</div>" ; /* end hashtag table div */

			echo "<div class='right' id='hashtagsBarChart'>" ; /** Bar chart for hashtags used **/
		    echo '<canvas id="tagsChart" class="barCharts" width="600" height="400"></canvas>' ;
			echo "</div>" ; /* end hashtag barchart */
			echo "</div>" ; /* end second tab content */
			echo "</li>" ; /* end second tab */
			
			echo "<li>" ;
			echo "<input type='radio' name='tabs' id='tab3'>" ;
			echo "<label for='tab3'> User Mentions </label>" ;
			echo "<div id='tab-content3' class='tab-content'>" ;
			echo "<div class='left' id='userMentions'>" ;
			/** Bar chart for userNames mentioned **/
			echo '<canvas id="usersChart" class="barCharts" width="600" height="400"></canvas>' ;
		    echo "</div>" ;
		    
			echo "<div class='right' id='userBarChart'>" ;
			/** Display the top ten (or less) users mentioned **/
			echo '<table class="flat-table flat-table-2">';
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
		    echo "</div>" ; /* end user table div */
		    echo "</div>" ; /* end third tab div */
		    echo "</li>" ; /* end third tab */
		    
			echo "<li>" ;
			echo "<input type='radio' name='tabs' id='tab4'>" ;
			echo "<label for='tab4'>Day Frequency</label>" ;
			echo "<div id='tab-content4' class='tab-content'>" ;
			echo "<div class='left weekStats' id='dayTable'>" ;
		    /** Display breakdown of percentage of tweets per weekday **/
		    echo '<table class="center">';

		    echo '<tr><td class="dark">Day of the Week</td>' .
			 '<td class="dark"> Percentage of Tweets Posted</td></tr>';

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
		    echo "</div>" ; /* end of day table div */
		    
			echo "<div class='right' id='dayPieChart'>" ;
		    /** Pie chart for days tweeted **/
		    echo '<canvas id="daysChart" width="400" height="400"></canvas>' ;
		    echo "</div>" ; /* end of day pie chart div */
		    echo "</div>" ; /* end of fourth tab content */
		    echo "</li>" ; /* end of fourth tab */
			
			echo "<li>" ;
			echo "<input type='radio' name='tabs' id='tab5'>" ;
			echo "<label for='tab5'>Word Cloud</label>" ;
			echo "<div id='tab-content5' class='tab-content'>" ;
		    echo '<div class="dark center"><br>Here is the word cloud generated from your tweets:<br></div>' ;
		    echo '<div id="wordcloud" class="dark center">Loading your wordcloud now!!!</div>' ;
		    echo "</div>" ;
		    echo "</li>" ;
		    
		    echo "</ul>" ;
		    echo "</div>" ; /* end tab holder div */
		    
		    /****** This is where we stop displaying our analysis ******/
        profileEventCompleted("echo all");
        }
        }
        
		    ?>


		    <script type="text/javascript">
		      // This part creates the live chart for the hashtags
		      var tagsText  = <?php echo json_encode($tagsTextCharts); ?>;
		      var tagsUsage = <?php echo json_encode($tagsUsageCharts); ?>;

		      tagsStep = Math.ceil(tagsUsage[0] / 10 ) ;

		      var tagsData = {
		        labels : tagsText,
		        datasets : [
		          {
		            fillColor : "#ff6969",
		            strokeColor : "#ff6969",
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
		            fillColor : "#ff6969",
		            strokeColor : "#ff6969",
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

		      new Chart(daysChart).Pie(daysData) ;
		    </script>

        <script>
          // nick

          // onpageload, make an ajax request to our wordcloud generating script
          $(function(){

            var jsonFilename= '<?php echo "$wordcloudJSONFileName"; ?>';

            console.log("making ajax request! serialized JSON: " + jsonFilename);

            $.ajax({

                url: "wordcloudFromJSON.php",

                data:  { d: jsonFilename }, // "pass" the object back to php: $_POST['d']

                type: 'POST', // post request bcos the request object may be many chars long

                success: function(data){

                    console.log("success, reply= " + data);
                    // http://stackoverflow.com/questions/540349/change-the-image-source-using-jquery
                    //$('#wordcloud').hide();
                    $('#wordcloud').html("<div id='wordcloudImg' style='background-image: url(" + data + ")'></div>");
                    //$('#wordcloud').slideDown();

                },

                error: function(){

                    console.log("ajax error");
                    $('#wordcloud').hide();

                }

            });

          });
        </script>
        </div>

        <div id="footer">
          <ul>
            <li><a href="about.html">About</a></li>
            <li><a href="contact.html">Contact</a></li>
            <li><a href="help.html">Help</a></li>
          </ul>
        </div>
		</div>

</body>
</html>
