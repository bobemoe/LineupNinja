<?
abstract class LineupNinjaPage extends RoutedPage{
  protected $ln=null;

  function __construct($ln){
    parent::__construct();
    $this->ln=$ln;
  }

  public function getFooter($data){
    return file_get_contents(__DIR__.'/templates/footer.html');
  }

  public function getHeader($data){
    return file_get_contents(__DIR__.'/templates/header.html');
  }

}
