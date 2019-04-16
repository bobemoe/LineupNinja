<!DOCTYPE html>
<html lang="en-GB">
<head>
<meta charset="UTF-8" />

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function add_shortcode($a,$b){
	return true;
}

require('LineupNinjaData.php');
require('LineupNinjaShortcode.php');

$config=require('config.php');

$lnd=new LineupNinjaData(__DIR__.'/data.srlz',$config);
$lns=new LineupNinjaShortcode($lnd);
// get test $data from URL or CLI ?? 
if( isset($argv[0]) ){
	$data=[];
	foreach(array_slice($argv,1) as $arg){
		list($k,$v)=explode('=',$arg);
		$data[$k]=$v;
	}
}else{
	$data=$_GET;
}
echo $lns->callback( $data );
echo "\n";
