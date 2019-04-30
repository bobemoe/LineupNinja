<?php

/**
Plugin Name: Lineup Ninja Intergration
Plugin URI: https://github.com/bobemoe/LineupNinja
Author: jhodges
Author URI: http://jhodges.co.uk
Description: Simple plugin to oputput LineupNinja data onto wordpress site.
Version: 1.0
*/

require_once('LineupNinjaData.php');
require_once('LineupNinjaShortcode.php');

require_once('RoutedPage.php');
require_once('LineupNinjaPage.php');
require_once('SessionPage.php');
require_once('LocationPage.php');
require_once('LabelPage.php');

$config=require('config.php');

$o=[];

foreach($config['feeds'] as $id=>$feed){
  //load the data
  $lnd=new LineupNinjaData(__DIR__.'/data'.$id.'.srlz', $feed);
  //initilize the shortcode
  new LineupNinjaShortcode($lnd);
  //initilize the pages
  new SessionPage($lnd);
  new LocationPage($lnd);
  new LabelPage($lnd);
}
