<?php
class LineupNinjaData{

  private $fname;
  private $data=null;
  private $config=[];

  public function __construct($fname,$config){
    $this->fname=$fname;
    $this->config=$config;
  }

  /* load the data into memory */
  private function initData(){
    if($this->data===null){
      //load the data
      $this->data=unserialize(file_get_contents($this->fname));
    }
  }

  public function getData(){
	   $this->initData();
	   return $this->data;
  }

  public function getEntityIdByName($entity,$name){
    $this->initData();
    $name=strtolower($name);
    foreach($this->data->$entity as $item){
      if(strtolower($item->name)==$name){
        return $item->id;
      }
    }
    return null;
  }

  public function getEntityByName($entity,$name){
    $this->initData();
    $name=strtolower($name);
    foreach($this->data->$entity as $item){
      if(strtolower($item->name)==$name){
        return $item;
      }
    }
    return null;
  }

  public function getConfig($key){
    return $this->config[$key];
  }

}
