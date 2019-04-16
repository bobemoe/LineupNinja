<?
class SessionPage extends LineupNinjaPage{
  protected $route_regexp='#(^|/)session/(.*)/?#';

  function setupPost($post,$matches){
    $data=$this->ln->getData();
    $session=$data->sessions[$matches[2]];

    $post->post_title = $session->name;
    $post->post_content = $this->getContent($session,$data);

    $post->post_parent=$this->ln->getConfig('post_parent'); 

    return($post);
  }

  function getContent($session,$data){
    $contribs='';
    $html='';
    foreach($session->contributors as $id){
      if($contribs==''){
        $contribs.='with ';
      }else{
        $contribs.=', ';
      }
      $contribs .= $data->contributors[$id]->firstName . ' '
      . $data->contributors[$id]->lastName;
    }
    $info=[];
    if($session->date){
      $info[]='<b>Time:</b> '.$session->date;
    }
    $labels='';
    foreach($session->labels as $id){
      if($labels==''){
        $labels.='<b>Labels:</b> ';
      }else{
        $labels.=' , ';
      }
      $labels .= '<a href="'.$this->ln->getConfig('url_prefix').'/label/'.urlencode($data->labels[$id]->name).'">' . $data->labels[$id]->name . '</a>';
    }
    $info[]=$labels;
    if($session->location){
      $info[]='<b>Location:</b> <a href="'.$this->ln->getConfig('url_prefix').'/location/'.urlencode($data->locations[$session->location]->name).'">'.$data->locations[$session->location]->name.'</a>';
    }
    $html='';
    if($contribs) $html.="<h3>$contribs</h3>";
    $str=implode('<br/>',array_filter($info));
    if($str) $html.="<p>$str</p>";
    $html.=$session->description_HTML;

    ///

    $list=[$html];
    foreach($session->contributors as $id){
      $contributor=$data->contributors[$id];
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
