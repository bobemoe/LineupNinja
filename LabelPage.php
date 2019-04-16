<?php
class LabelPage extends LineupNinjaPage{
  protected $route_regexp='#(^|/)label/(.*)/?#';

  function setupPost($post,$matches){
    $label=$this->ln->getEntityByName('labels',urldecode($matches[2]));

    $post->post_title = $label->name;
    $post->post_content = '[LineupNinja type=sessions label="'.$label->name.'"]'; 

    $post->post_parent=$this->ln->getConfig('post_parent'); 

    return($post);
  }

}
