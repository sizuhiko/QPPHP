<?php
/** @file
 * This extension provides support for PHP code block operations.
 */

/**
 * PHP code block injection for QueryPath.
 *
 */ 
class QPPHP implements QueryPathExtension {
  protected $qp = NULL;
  public function __construct(QueryPath $qp) {
    $this->qp = $qp;
  }

  public function attrPHP($attr, $code) {
    return $this->qp->attr($attr, new QPPHPCode($code));
  }
  public function php() {
    $html = QPPHPCode::attrToPHP($this->qp->top()->innerHTML());
    return $html;
  }


}
QueryPathExtensionRegistry::extend('QPPHP');


class QPPHPCode {
  private $id;
  public $code;
  protected static $map;

  public function __construct($base) {
    $this->id = spl_object_hash($this);
    $this->code = '<?php '.$base.' ?>';
    self::$map[$this->id] = $this;
  }

  public function __toString() {
    return 'php:include('.$this->id.')';
  }

  public static function attrToPHP($html) {
    $cnt = preg_match_all("/[A-Za-z0-9_\-]+\s*=\s*['\"]php:include\(([a-zA-Z0-9]+)\)['\"]/", $html, $matches);
    if($cnt == 0) return;
    foreach ($matches[1] as $key => $value) {
      if(isset(self::$map[$value])) {
        $replace = str_replace(self::$map[$value], self::$map[$value]->code, $matches[0][$key]);
        $html = str_replace($matches[0][$key], $replace, $html);
      }
    }
    return $html;
  }
}
