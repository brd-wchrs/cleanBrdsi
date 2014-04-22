<?php
/**
 * @file
 * Take the user when they return from Twitter. Get access tokens.
 * Verify credentials and redirect to based on response from Twitter.
 */
/*
 *  here's an example of a url from twitter:
 *
 *  http://brdsi.bricreates.com/twitteroauth/callback.php?oauth_token=ITwQ9CIn5GDxzCIHbbSUbdV5I8u3iSEZjm3uBn6mUE&oauth_verifier=Hgty81K96A6XbGbTa1xsg5LccdJAZ3qy9HvxqhJQEY
 *
 *  it has 2 things in it: an oauth_token, and an oauth_verifier
 *
 */


/* Start session and load lib */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');

/* If the oauth_token is old redirect to the connect page. */
if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
  $_SESSION['oauth_status'] = 'oldtoken';
  header('Location: ./clearsessions.php');
}

/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

/* Request access tokens from twitter */
$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

$_SESSION['access_token'] = $access_token;

// get account credentials from twitter
$user_info = $connection->get('account/verify_credentials');


// save the oauth tokens into a database
$db_con = mysql_connect("bricreatescom.ipagemysql.com", "brdsi_team", "generalPassword92!");



if ($db_con) { // this next part thanks to http://www.w3schools.com/php/php_mysql_insert.asp
  // remove when stable
  //echo 'Connected successfully';
  mysql_select_db("brdsi_backend");


  /* this block of code taken directly from
   * http://code.tutsplus.com/tutorials/how-to-authenticate-users-with-twitter-oauth--net-13595
   */
  if (isset($user_info->error)) {
    // Something's wrong, go back to square 1
    // @todo what is our login page?
    // header('Location: twitter_login.php');
    echo "error in user info from twitter i guess<br>";
  } else {
    // Let's find the user by its ID
    $query = mysql_query("SELECT * FROM users WHERE oauth_provider = 'twitter' AND oauth_uid = '" . $user_info->id . "';");
    if (!$query) {
      echo "ERROR: database select 1 failed: " . mysql_error();
    }
    

    $result = mysql_fetch_array($query);

    // If not, let's add it to the database
    if (empty($result)) {

      $query = mysql_query("INSERT INTO users (oauth_provider, oauth_uid, username, oauth_token, oauth_secret) VALUES ('twitter', " .
              "'{$user_info->id}', '{$user_info->screen_name}', '{$access_token['oauth_token']}', '{$access_token['oauth_token_secret']}');");
      if (!$query) {
        echo "ERROR: database insert failed: " . mysql_error();
      }

      $query = mysql_query("SELECT * FROM users WHERE id = '" . mysql_insert_id() . "';");
      if (!$query) {
        echo "ERROR: database select 2 failed: " . mysql_error();
      }

      $result = mysql_fetch_array($query);
    } else {
      // Update the tokens
      $query = mysql_query("UPDATE users SET oauth_token = '{$access_token['oauth_token']}', oauth_secret = '{$access_token['oauth_token_secret']}' WHERE oauth_provider = 'twitter' AND oauth_uid = {$user_info->id}");
    }

    $_SESSION['id']              = $result['id'];
    $_SESSION['username']        = $result['username'];
    $_SESSION['oauth_uid']       = $result['oauth_uid'];
    $_SESSION['oauth_provider']  = $result['oauth_provider'];
    $_SESSION['oauth_token']     = $result['oauth_token'];
    $_SESSION['oauth_secret']    = $result['oauth_secret'];

    //header('Location: twitter_update.php');
  }

  // otherwise, the info is now in the db, and we're done with the db for now..
  mysql_close($db_con); /* <<< Look at how responsible we are <<< */
} else {

  echo "warning no database connection";
}


/* Remove no longer needed request tokens */
unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);

/* If HTTP response is 200 continue otherwise send to connect page to retry */
if (200 == $connection->http_code) {
  /* The user has been verified and the access tokens can be saved for future use */
  $_SESSION['status'] = 'verified';
  
  
  //$destinationURL="./index.php";
  $destinationURL="/ndev/cleanBrdsi/main.php";
  header("Location: $destinationURL");
} else {
  /* Save HTTP status for error dialog on connnect page. */
  header('Location: ./clearsessions.php');
}
