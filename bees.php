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
        <meta name="viewport" content="width=device-width">

        <!-- stylesheets -->
        <link rel="stylesheet" href="styles/reset.css">
        <link rel="stylesheet" href="styles/main.css">
        <link rel="stylesheet" href="styles/homeycombs.css">
        <link rel="stylesheet" href="styles/statsTable.css">
        <!-- *********** -->

        <!-- favicon -->
        <link rel="icon" type="image/png" href="images/favicon.ico">

        <!-- Google Fonts -->
      	<link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700' rel='stylesheet' type='text/css'>
        <!-- endbuild -->

        <!-- jQuery -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="scripts/honey.js"></script>

        <!-- For hexagon script -->
        <script type="text/javascript">
            // this makes the honeycombs look like honeycombs.
            // it runs after page load.
             $(function () {
                console.log("Running homeyComb!!!!!");
                $('.honeycombs').honeycombs({
                combWidth: 200,
                margin: 10
                });
             });
        </script>
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
            <?php require 'pages/trendCache.php' ?>
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
