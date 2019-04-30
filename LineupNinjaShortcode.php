<?php
class LineupNinjaShortcode{

  private $lnd=null;

  /* constructor; register the shortcode */
  public function __construct($lnd){
    add_shortcode( $lnd->getConfig('shortcode'), [$this, 'callback'] );
    $this->lnd=$lnd;
  }

  /* the callback function called by wordpress shortcode */
  public function callback($args){
    $func='process'.ucwords($args['type']);
    if( method_exists( $this, $func ) ){
      $args=$this->parseArgs($args);
      return $this->$func($args);
    }else{
      return "Error: unknown type {$args['type']}";
    }
  }

  public function getData(){
  	return $this->lnd->getData();
  }

  /* convert argument names to id's and valadate options */
  private function parseArgs($args){
      //load the list of locations from the args
      $locations=[];
      if(isset($args['location'])){
        $args['locations']=$args['location'];
        unset($args['location']);
      }
      if(isset($args['locations'])){
        foreach(explode(',',$args['locations']) as $name){
          $location=$this->lnd->getEntityIdByName('locations',trim($name));
          if($location){
            $locations[]=$location;
          }
        }
      }
      $args['locations']=$locations;
      //load the list of labels from the args
      $labels=[];
      if(isset($args['label'])){
        $args['labels']=$args['label'];
        unset($args['label']);
      }
      if(isset($args['labels'])){
        foreach(explode(',',$args['labels']) as $name){
          $label=$this->lnd->getEntityIdByName('labels',trim($name));
          if($label){
            $labels[]=$label;
          }
        }
      }
      $args['labels']=$labels;
      //check order
      if(!isset($args['order'])){
        $args['order']='az';
      }
      if(!in_array($args['order'],$orders=['az','time'])) throw new exception("Order '$order' must be one of: ".implode(',',$orders));
      //done
      return $args;
  }

  /****/

  private function processContributors($args){
    //now find the contributors that have sessions in those locations or labels
    $contributors=[];
    $sort=[];
    foreach($this->getData()->contributors as $contributor){
      if($args['locations'] && !$this->isContributorInLocations($contributor,$args['locations']) ) continue;
      if($args['labels'] && !$this->isContributorInLabels($contributor,$args['labels']) ) continue;
      $contributors[$contributor->id]=$contributor;
      $sort[$contributor->id]=$contributor->firstName;
    }
    array_multisort($sort,SORT_ASC,$contributors);
    return $this->contributorsToHtml($contributors);
  }

  private function processSessions($args){
    //now find the sessions in those locations or labels
    $sessions=[];
    $sort=[];
    foreach($this->getData()->sessions as $session){
      if( $args['locations'] && !$this->isSessionInLocations($session,$args['locations']) ) continue;
      if( $args['labels'] && !$this->isSessionInLabels($session,$args['labels']) ) continue;
      $sessions[]=$session;
      if($args['order']=='time'){
	if($session->startDate){
	        $sort[]=$session->startDate->getTimestamp();
        }else{
		$sort[]=0;
      }
      }elseif($args['order']='az'){
        $sort[]=trim($session->name);
      }
    }
    array_multisort($sort,SORT_ASC,$sessions);
    return $this->sessionsToHtml($sessions);
  }

  private function processLabels($args){
    $items=[];
    $sort=[];
    foreach($this->getData()->labels as $item){
      $items[]='<a href="/'.$this->lnd->getConfig('url_prefix').'/label/'.urlencode($item->name).'">'.$item->name.'</a>';
      $sort[]=$item->name;
    }
    array_multisort($sort,SORT_ASC,$items);
    return '<ul><li>'.implode('</li><li>',$items).'</li></ul>';
  }

  private function processLocations($args){
    $items=[];
    $sort=[];
    foreach($this->getData()->locations as $item){
      $items[]='<a href="/'.$this->lnd->getConfig('url_prefix').'/location/'.urlencode($item->name).'">'.$item->name.'</a>';
      $sort[]=$item->name;
    }
    array_multisort($sort,SORT_ASC,$items);
    return '<ul><li>'.implode('</li><li>',$items).'</li></ul>';
  }

  /****/

  private function isSessionInLocations($session,$locations){
    //lets see if the session is shceduled
    if($session->location){
      //is is scheduled into one of the locations we're searching?
      foreach($locations as $location){
        if($session->location==$location) return true;
      }
    }
    return false;
  }

  private function isSessionInLabels($session,$labels){
    if($session->labels){
      foreach($labels as $label){
        if(in_array($label, $session->labels)) return true;
      }
    }
    return false;
  }

  private function isContributorInLabels($contributor,$labels){
    if($contributor->labels){
      foreach($labels as $label){
        if(in_array($label->id,$contributor->labels)) return true;
      }
    }
    return false;
  }

  private function isContributorInLocations($contributor,$locations){
    //check all of the session for the contributor, to see if they are in any of the locations
    foreach($contributor->sessions as $sessionId){
      $session=$this->getData()->sessions[$sessionId];
      if($this->isSessionInLocations($session,$locations)){
        return true;
      }
    }
    return false;
  }

  public function sessionsToHtml($sessions){
    $html='<ul>';
    foreach($sessions as $session){
	$contribs='';
      foreach($session->contributors as $id){
        if($contribs==''){
          $contribs.=' with ';
        }else{
          $contribs.=', ';
        }
        $contribs .= $this->getData()->contributors[$id]->firstName . ' '
        . $this->getData()->contributors[$id]->lastName;
      }
      $location='';
      if($session->date){
        $location.= $session->date;
      }
      if($session->location){
        $location.= ' at '.$this->getData()->locations[$session->location]->name;
      }else{
        $location=null;
      }
      $html.='<li><strong><a href="/'.$this->lnd->getConfig('url_prefix').'/session/'.$session->id.'/">'.$session->name.'</a></strong> ';
      $html.= implode(', ',array_filter([$contribs,$location]));
      $html.='</li>';
    }
    return $html.'</ul>';
  }

  public function contributorsToHtml($contributors){
    $list=[];
    foreach($contributors as $contributor){
      $list[]='<h2>'.$contributor->firstName.' '.$contributor->lastName.'</h2>'.$contributor->bio_HTML
        .($contributor->twitter?"<br/><a href=\"{$contributor->twitter}\">{$contributor->twitter}</a> ":'')
        .($contributor->facebook?"<br/><a href=\"{$contributor->facebook}\">{$contributor->facebook}</a> ":'')
        .($contributor->linkedin?"<br/><a href=\"{$contributor->linkedin}\">{$contributor->linkedin}</a> ":'')
        .($contributor->google?"<br/><a href=\"{$contributor->google}\">{$contributor->google}</a> ":'')
      ;
    }
    return implode('<hr/>',$list);
  }

}

