<?php
/**
 * hexaTrend.php
 * nicholas st.pierre
 *
 * march 23 2014
 *
 * this file uses cities.php to obtain trends from some common cities.
 * It then spits some html out
 *
 *
 */
  // if we run out of requests, use this html
  // include 'sampleHexagons.html'

  include('../cities.php');

  // goofy function to facilitate correct hexa locations
  // returns a string. will return empty string on failure.
  function getCoordStrFromCityname($cityname)
  {
    
    // PHP allows switching on strings, somehow
    switch ($cityname)
    {
      
        case "Boston":
          return "lat=42.36&lng=-71.06";

          
        case "Los Angeles":
          return "lat=34.05&lng=-118.25";

          
        case "Atlanta":
          return "lat=33.755&lng=-84.39";

          
        default:
          // error code goes here
          break;
        
    }    
   
    // if we reach here then report failure?
    return "";
    
  }
  
  /**
   * 
   * @param type $arr
   * @param type $cityname
   * @param type $num
   * @return string
   */
  function Honey($arr, $cityname, $num){

      $i = 1;
      $tweetViewURL = "tweetLook.php";
      //$tweetViewURL= "#trend";      

      $reply  = "";
      $coords = getCoordStrFromCityname($cityname);

      foreach($arr as $honeycomb) {
        
        $href = "$tweetViewURL?q=". urlencode($honeycomb) ."&$coords&rad=500";        
        $reply .= "<div class='comb'><span><p><a href='$href'>$cityname<br>_____<br><br> $honeycomb</a></p>\n</span></div>\n";

        //$href="$tweetViewURL/". urlencode($honeycomb);
        //$reply .= "<div class='comb'><span><p><a href='$tweetViewURL/". urlencode($honeycomb)."'>$cityname<br>_____<br><br> $honeycomb</a></p>\n</span></div>\n";        
       
        if( $i === $num ) {
          break 1;
        }
        
        $i++;
      }

      return $reply;
      
  }  /***********  end function  ***********/
 


  /*************** main script ***************/

  // @ todo this doesn't _really_ catch the errors, does it?
  if( ! isset($trendsBoston)  ||
      ! isset($trendsAtlanta) ||
      ! isset($trendsLA))  die("<div>Out of requests! Please try again later.</div>");

  $response  = '<div class="content">';
  $response .= '<div class="honeycombs">';
  $response .= Honey($trendsBoston,   "Boston",      8) ;
  $response .= Honey($trendsLA,       "Los Angeles", 8) ;
  $response .= Honey($trendsAtlanta,  "Atlanta",     8) ;
  $response .= "</div></div>";

  
  echo $response;
  

?>
