<?php
class LabelPage extends LineupNinjaPage{
  protected $route_regexp;

  function __construct($lnd){
    $this->route_regexp='#'.$lnd->getConfig('url_prefix').'/label/(.*)/?#';
    parent::__construct($lnd);
  }

  function setupPost($post,$matches){
    $label=$this->lnd->getEntityByName('labels',urldecode($matches[1]));

    $post->post_title = $label->name;
    $post->post_content = '['.$this->lnd->getConfig('shortcode').' type=sessions label="'.$label->name.'"]'; 

    $post->post_parent=$this->lnd->getConfig('post_parent'); 

    return($post);
  }

}
