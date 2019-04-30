<?php
$config=require(__DIR__.'/config.php');

$id=intval($_GET['id']);

$ch = curl_init();
curl_setopt ($ch, CURLOPT_URL, $config['feeds'][$id]['api_url']);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
$json = curl_exec($ch);

if(curl_errno($ch)){
	$res=['result'=>'curl_error','reason'=>curl_error($ch)];
}else{
	$data=json_decode($json);
	if(isset($data->error)){
		$res=['result'=>'api_error','reason'=>$data->error];
	}elseif(isset($data->contributors)){
		$srlz=serialize(preprocess($data));
		file_put_contents('data'.$id.'.srlz',$srlz);
		if($config['archive']){
			file_put_contents('data_archive/'.$id.'-'.time().'.srlz',$srlz);
		}
		$res=['result'=>'success'];
	}else{
		$res=['result'=>'error','reason'=>'no contributors? valid json?'];
	}
}

curl_close($ch);

echo json_encode($res);
die("\n");

function preprocess($data){
	$startDateFormat="D jS, g:i a";
	$endDateFormat="g:i a";
	//convert the 0...n indexes to UUID's for these arrays
	foreach(array('contributors','sessions','locations','tracks','labels') as $path){
		$indexed=[];
		foreach($data->$path as $k=>$v){
			$indexed[$v->id]=$v;
		}
		$data->$path=$indexed;
	}
	foreach($data->sessions as $id=>$s){
		//bit of a hack until LN support drop in sessions
		//any sessions with a inLocation rule and no Location, we will set the location to the inLocation!
		if($s->location==null && isset($s->rules->inLocations[0])){
			$data->sessions[$id]->location=$s->rules->inLocations[0];
		}
		//convert dates
		if($s->startDate && $s->endDate){
			$s->startDate=datefix($s->startDate);
			$s->endDate=datefix($s->endDate);
			$s->date=$s->startDate->format($startDateFormat).
			' - '. $s->endDate->format($endDateFormat);
		}else{
			$s->date=null;
		}
	}
	return $data;
}

function datefix($value){
    $dt = new DateTime($value, new DateTimeZone('UTC'));
    $dt->setTimezone(new DateTimeZone('Europe/London'));
    return $dt;
}
