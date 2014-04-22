<?php
/**
 * nick stpierre
 * 3.30.2014
 * gotta go fast
 * 
 */


/***************** INIT VARS **********************/

// this seems to do nothing but it /should/ make php spew errors
error_reporting(E_ALL);

// name of the file that will contain the cached html
$cachedFilename =  is_dir('pages') ? "pages/cachedHexas.html" : "cachedHexas.html";


/****************************************************/



/*********** DEFINE HELPER FUNCTIONS ****************/


/**
 * goofy function to facilitate correct hexa locations
 * @param string $cityname
 * @return string
 */
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
      $tweetViewURL = "tweetLookPage.php";
      //$tweetViewURL= "#trend";      

      $reply  = "";
      $coords = getCoordStrFromCityname($cityname);

      foreach($arr as $honeycomb) {
        
        $href = "$tweetViewURL?q=". urlencode($honeycomb) ."&$coords&rad=500";        
        $reply .= "<a href='$href'><div class='comb'><span><p>$cityname<br>_____<br><br> $honeycomb</p></span>\n</div></a>\n";

        //$href="$tweetViewURL/". urlencode($honeycomb);
        //$reply .= "<div class='comb'><span><p><a href='$tweetViewURL/". urlencode($honeycomb)."'>$cityname<br>_____<br><br> $honeycomb</a></p>\n</span></div>\n";        
       
        if( $i === $num ) {
          break 1;
        }
        
        $i++;
      }

      return $reply;
}

/**
 * function to getAndStoreHexaHTML
 * returns FALSE on failure, TRUE otherwise
 */
function getAndStoreHexaHTML()
{
  
  //$cachedFilename = "cachedHexas.html";

  $cachedFilename =  is_dir('pages') ? "pages/cachedHexas.html" : "cachedHexas.html";

  
  // this script sets $trendsBoston, $trendsAtlanta, $trendsLA
  include('../cities.php');

  // check if the script had any trouble or if we're out of requests
  // hey this might not work uhhh @todo later?
  if( ! isset($trendsBoston)  ||
      ! isset($trendsAtlanta) ||
      ! isset($trendsLA)) 
    {
    //echo "trends arrays are empty, we're dead";
    return FALSE;
    
    } else {

  $response  = '<div class="content">';
  $response .= '<div class="honeycombs">';
  $response .= Honey($trendsBoston,   "Boston",      8) ;
  $response .= Honey($trendsLA,       "Los Angeles", 8) ;
  $response .= Honey($trendsAtlanta,  "Atlanta",     8) ;
  $response .= "</div></div>";
  }

  // get the absolute path to the file
  $fullpath = getcwd() . "/$cachedFilename";
  
  // open it
  $handle =  fopen($fullpath, "w");
  
  // error check opening the file
  if(! $handle)
  {
    //echo "ERROR can't open file";
    return FALSE;
  }
  
  // error check writing to the file
  if( fwrite($handle, $response) === FALSE)
  {
    //echo "ERROR file write error";
    return FALSE;
  }
  
    // close the file and honestly, forget error checking.
    fclose($handle);

    // true means OK
    return TRUE;
}

/******************************************************/


/******************** SCRIPT BODY *********************/


// first check to see if our cached file exists
if(! file_exists($cachedFilename))
{
  //echo "file dont exists<br>";
  
  // if not exists
  if( getAndStoreHexaHTML() )
    echo file_get_contents($cachedFilename);
  else
    echo "ERROR Couldn't get an initial cache";
  
}
else
{ 
 
  //echo "file exists<br>";

  // get file modified time
  $modtime = filemtime($cachedFilename);
  
  
  // error check this
  if( $modtime === FALSE )
  {
    die("ERROR filemtime failed");
  }

  // time() gives us epoch seconds, and so does modtime()
  $fileAge = time() - $modtime;
  
  //echo "debug: now is " . time() . ", modtime is $modtime, and their difference is $fileAge <br>";

  // If the file is old, refresh it:
  // 900 seconds = 15 minutes
  if( $fileAge > 900 || $_GET['cache'] === 'reset' )
  {
    if(! getAndStoreHexaHTML())
    {
      //echo "we have a cached version but the refresh failed...<br>";
    }
    else{
      //echo "update success<br>";
    }
  }
  else
  {
    echo "\n<!-- the cached file is $fileAge seconds old -->\n";
    //echo "using cached<br>";
    
  }
  
  
  
  echo file_get_contents($cachedFilename);
  
} // end else {?>