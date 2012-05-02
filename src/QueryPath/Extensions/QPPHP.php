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
    return $this->qp->attr($attr, '<?php '.$code.' ?>');
  }
  public function php() {
    print $this->qp->top()->innerHTML();
    return $this->qp->top()->innerHTML();
  }
}
QueryPathExtensionRegistry::extend('QPPHP');


class QPPHPCode {
  public $code;
}
