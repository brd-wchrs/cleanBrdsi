<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

<!--(C) Bri Gainley, Alan Estrada, Nick St. Pierre, 2014 -->

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>brdsi - A Twitter Analysis Tool</title>
        <meta name="description" content="Twitter trend analysis, delivered right to your screen">
        <meta name="viewport"    content="width=device-width">

        <!-- stylesheets -->
        <link rel="stylesheet" href="styles/reset.css">
        <link rel="stylesheet" href="styles/main.css">
        <!--link rel="stylesheet" href="styles/homeycombs.css"-->
        <link rel="stylesheet" href="styles/statsTable.css">
        <link rel="stylesheet" href="styles/tweetLookPage.css">    
        <!-- *********** -->

        <!-- favicon -->
        <link rel="icon" type="image/png" href="images/favicon.ico">

        <!-- Google Fonts -->
      	<link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700' rel='stylesheet' type='text/css'>
        <!-- endbuild -->

        <!-- jQuery -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <!--script src="scripts/honey.js"></script-->

        <!-- For back button  -->
        <script type="text/javascript">
            
            // on page load, set an onclick function for the Back button
            $(function(){
              $(".btn").click(function(){
                console.log("Backbutton callback running");
                window.history.back();
              });
            });
            
        </script>
        <style> 
          
          /* page-too-short fix */
          body { margin-bottom: 40px; }
          
          /* style our back button */
          .btn { 
            position:  fixed;
            left:      12px;
            top:       45%;
          }

          /* back button hover style */
          .btn:hover { 
            cursor:           pointer;
          }

          /** 
           * fix to make the table cells the correct height, and
           * make the cell contents align
          **/
          .statsTable td{
            
            vertical-align: middle;
          }
            
        </style>
    </head>

    <body>
          <!-- navbar -->
	        <div id="top-navbar">
            <a href="bees.php"><img src="images/brdsiLogo.png"></a>
            <ul>
              <li><a href="timeline.php">Timeline Analysis</a></li>
              <li><a href="maps.php">Region Graph</a></li>
              <li><a href="friendTrends.php">Friend Trends</a></li>
            </ul>
          </div>
          <!-- end navbar -->

          <!-- main content -->
	        <div>

            <div class='btn'>Back</div>
            <div class="center">
              <div class="statsTable">
                <table class="flat-table flat-table-2">
                  <tr>
                    <td></td><td>User</td><td>Tweet</td>
                  </tr>
            
                  <?php  // code to Get the tweets and fill in the above table //

                      /* This is from Alan's code! */

                      require_once('TwitterAPIExchange.php');

                      /** Set access tokens here - see: https://dev.twitter.com/apps/ **/
                      $settings = array(
                        'oauth_access_token'        => "2326287732-4iSDtAQB7nhc7EsvxFNUgOBg5LJpdqYvbQZEP0C",
                        'oauth_access_token_secret' => "pBJ4aPRnfkEBBAqOUK9k8GDf7LHJBD2xPV4w0tmphYUhf",
                        'consumer_key'              => "y7ojWz9W1EAqeFyK5BsHfA",
                        'consumer_secret'           => "atcvh1uWEdNNZ2hnlUrcrFuE1yTqlnRHDpToVxik"
                      );


                      /* Now we create the URL that requests the trends of the WOEid */
                      $url = 'https://api.twitter.com/1.1/search/tweets.json';

                      // make a twitter object from the api
                      $twitter = new TwitterAPIExchange($settings);

                      /* Get params from the script, or fallback onto these defaults */
                      $latitude   = isset( $_GET['lat'] ) ? $_GET['lat'] : "42.35"  ;
                      $longitude  = isset( $_GET['lng'] ) ? $_GET['lng'] : "-71.06" ;
                      $radius     = isset( $_GET['rad'] ) ? $_GET['rad'] : "500"    ;
                      $searchStr  = isset( $_GET['q'] )   ? $_GET['q']   : "pokemon";

                      // manually treat +'s in the url
                      $searchStr  =  str_replace(" ", "", $searchStr);
                      $searchStr  =  str_replace("+", "", $searchStr);

                      $getfield = "?q=$searchStr&geocode=$latitude,$longitude,$radius".'mi&count=15' ;

                      $resultJSON = $twitter->setGetfield($getfield)
                                            ->buildOauth($url, 'GET')
                                            ->performRequest();


                      // NRS: April 9: have this script just return the json
                      // echo $resultJSON;

                      // more alan code


                      $tweetsJSON = json_decode( $resultJSON, true );


                      foreach($tweetsJSON['statuses'] as $status) {
                          $tweetLink = 'https://twitter.com/' . $status["user"]["id_str"] . '/statuses/' . $status["id_str"] ;

                      
                          //echo '<tr onclick="window.document.location=\'' . $tweetLink . '\'"><td><img src="' . $status['user']['profile_image_url'] . '"/></td>';
                          
                          echo '<tr onclick="window.open(\'' . $tweetLink . '\', \'_blank\');"><td><img src="' . $status['user']['profile_image_url'] . '"/></td>';
                          
                          echo '<td>' . $status['user']['name'] . '</td>';
                          echo '<td>' . $status['text'] . '</td></tr>';
                        }

                  ?>
                </table>
              </div>
            </div>
          
          </div>
          <!-- end main content -->

          <!-- footer -->
	        <div id="footer">
	        	<ul>
              <li><a href="about.html">About</a></li>
              <li><a href="contact.html">Contact</a></li>
              <li><a href="help.html">Help</a></li>
            </ul>
          </div>
          <!-- end footer -->
</body>
</html>
