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

//load the LN data, put into data.srlz by publish.php 
$lnd=new LineupNinjaData(__DIR__.'/data.srlz', $config);

//initilize the shortcode
$lns=new LineupNinjaShortcode($lnd);

//initilize the pages
$sp=new SessionPage($lnd);
$lp=new LocationPage($lnd);
$lp=new LabelPage($lnd);
