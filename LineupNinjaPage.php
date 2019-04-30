<?
abstract class LineupNinjaPage extends RoutedPage{
  protected $lnd=null;

  function __construct($lnd){
    parent::__construct();
    $this->lnd=$lnd;
  }

  public function getFooter($data){
    return file_get_contents(__DIR__.'/templates/footer.html');
  }

  public function getHeader($data){
    return file_get_contents(__DIR__.'/templates/header.html');
  }

}
