<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title></title>
  </head>
  <body>
    <?php
    
      // from http://www.php.net/manual/en/curl.examples.php
      function cURLcheckBasicFunctions() 
      { 
        if( !function_exists("curl_init") && 
            !function_exists("curl_setopt") && 
            !function_exists("curl_exec") && 
            !function_exists("curl_close") ) return false; 
        else return true; 
      }         
      
      echo cURLcheckBasicFunctions() ? "we good" : "fk, nope, no curl here" ;
      
      
      ?>
  </body>
</html>
