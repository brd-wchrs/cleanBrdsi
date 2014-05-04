<?php


// nick


// defferred page for making wordclouds

require_once('wordCloudMaker.php') ;


if(! isset($_POST['d']))
  die("!MISSING DATA ARG");

if( empty($_POST['d']))
  die("!NO DATA");

$json = unserialize( file_get_contents( $_POST['d'])) ;

echo generateWordCloud( $json, 800, 800 ) ;

unlink($_POST['d']);
