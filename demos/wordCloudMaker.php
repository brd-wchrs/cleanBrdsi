<?php

  /** Accepts a json containing the results of a user_timeline 
      Twitter query and the height and width of the word cloud
      to be generated **/
  function generateWordCloud( $json, $height, $width ) 
  {
    $text = '' ;
    foreach ( $json as $tweet ) {
      $text .= ' ' ;
      $text .= $tweet['text'] ;
    }

    require_once('libraries/unirest-php/lib/Unirest.php');

    $response = Unirest::post(
      "https://gatheringpoint-word-cloud-maker.p.mashape.com/index.php",
      array(
        "X-Mashape-Authorization" => "OkVWcztYb4IzHPzPd41C7uo1xWWaaB1s"
      ),
      array(
        "height" => $height,
        "textblock" => $text,
        "width" => $width,
        "config" => "n\/a"
      )
    );

  return $response->body->url ;
  }
?>
