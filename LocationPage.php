<?php
class LocationPage extends LineupNinjaPage{
  protected $route_regexp='#(^|/)location/(.*)/?#';

  function setupPost($post,$matches){
    $location=$this->ln->getEntityByName('locations',urldecode($matches[2]));

    $post->post_title = $location->name;
    $post->post_content = '[LineupNinja type=sessions location="'.$location->name.'"]'; 

    $post->post_parent=$this->ln->getConfig('post_parent');

    return($post);
  }

}
