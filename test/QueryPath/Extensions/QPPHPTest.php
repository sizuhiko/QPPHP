<?php
/**
 * Tests for the QueryPath PHP Injection Extension.
 * @author sizuhiko
 * @license The GNU Lesser GPL (LGPL) or an MIT-like license.
 */

require_once 'PHPUnit/Autoload.php';
require_once 'vendor/querypath/QueryPath/src/QueryPath/QueryPath.php';
require_once 'src/QueryPath/Extensions/QPPHP.php';

class QPListTests extends PHPUnit_Framework_TestCase {

  protected $qp;

  protected function setUp() {
    $html = '<html>
      <head>
        <title>This is the title</title>
      </head>
      <body>
        <div id="one">
          <div id="two" class="class-one" data-url="php:include(0)">
            <div id="three">Inner text.</div>
          </div>
        </div>
        <span class="class-two">Nada</span>
        <ul>
          <li class="Odd" id="li-one">Odd</li>
          <li class="even" id="li-two">Even</li>
          <li class="Odd" id="li-three">Odd</li>
          <li class="even" id="li-four">Even</li>
          <li class="Odd" id="li-five">Odd</li>
          <li class="even" id="li-six">Even</li>
          <li class="Odd" id="li-seven">Odd</li>
          <li class="even" id="li-eight">Even</li>
          <li class="Odd" id="li-nine">Odd</li>
          <li class="even" id="li-ten">Even</li>
        </ul>
      </body>
    </html>';
    $this->qp = htmlqp($html);
  }

  public function testAttrPHP() {
    $this->qp->find('#one')->attrPHP('class', 'echo $oneClassName');
    $result = htmlqp($this->qp->php());
    $this->assertEquals('<?php echo $oneClassName ?>', $result->find('#one')->attr('class'));
  }
  public function testNotUseAttrPHP() {
    $result = htmlqp($this->qp->php());
    $this->assertEquals('php:include(0)', $result->find('#two')->attr('data-url'));
  }
  public function testAppendPHP() {
    $this->qp->find('#two')->appendPHP('echo "appendPHP in #two"');
    $this->assertRegExp('/<div id="three">Inner text.<\/div>\s*<\?php echo "appendPHP in #two" \?>/',$this->qp->php());
  }

}
