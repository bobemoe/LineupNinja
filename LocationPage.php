<?php
class LocationPage extends LineupNinjaPage{
  protected $route_regexp;

  function __construct($lnd){
    $this->route_regexp='#'.$lnd->getConfig('url_prefix').'/location/(.*)/?#';
    parent::__construct($lnd);
  }

  function setupPost($post,$matches){
    $location=$this->lnd->getEntityByName('locations',urldecode($matches[1]));

    $post->post_title = $location->name;
    $post->post_content = '['.$this->lnd->getConfig('shortcode').' type=sessions location="'.$location->name.'"]'; 

    $post->post_parent=$this->lnd->getConfig('post_parent');

    return($post);
  }

}
