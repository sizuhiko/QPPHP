<?php
/**
 * Tests for the QueryPath PHP Injection Extension.
 * @author sizuhiko
 * @license The GNU Lesser GPL (LGPL) or an MIT-like license.
 */

require_once 'PHPUnit/Autoload.php';
require_once 'vendor/querypath/QueryPath/src/QueryPath/QueryPath.php';
require_once 'src/QueryPath/Extensions/QPPHP.php';

class QPPHPTests extends PHPUnit_Framework_TestCase {

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
  public function testMultiAttrPHP() {
    $this->qp->find('ul li')->attrPHP('class', 'echo ($i % 2) == 0 ? "Odd" : "even"');
    $result = $this->qp->php();
    $this->assertRegExp('/<li class="<\?php echo \(\$i % 2\) == 0 \? "Odd" : "even" \?>" id="li-one">Odd<\/li>/', $result);
    $this->assertRegExp('/<li class="<\?php echo \(\$i % 2\) == 0 \? "Odd" : "even" \?>" id="li-ten">Even<\/li>/', $result);
  }
  public function testNotUseAttrPHP() {
    $result = htmlqp($this->qp->php());
    $this->assertEquals('php:include(0)', $result->find('#two')->attr('data-url'));
  }
  public function testAppendPHP() {
    $this->qp->find('#two')->appendPHP('echo "appendPHP in #two"');
    $this->assertRegExp('/<div id="three">Inner text.<\/div>\s*<\?php echo "appendPHP in #two" \?>/',$this->qp->php());
  }
  public function testPrependPHP() {
    $this->qp->find('#two')->prependPHP('echo "prependPHP in #two"');
    $this->assertRegExp('/<\?php echo "prependPHP in #two" \?>\s*<div id="three">Inner text.<\/div>/',$this->qp->php());
  }
  public function testBeforePHP() {
    $this->qp->find('.class-two')->beforePHP('echo "add php before .class-two"');
    $this->assertRegExp('/<\?php echo "add php before \.class-two" \?>\s*<span class="class-two">Nada<\/span>/',$this->qp->php());
  }
  public function testAfterPHP() {
    $this->qp->find('.class-two')->afterPHP('echo "add php after .class-two"');
    $this->assertRegExp('/<span class="class-two">Nada<\/span>\s*<\?php echo "add php after \.class-two" \?>/',$this->qp->php());
  }
  public function testWrapPHP() {
    $this->qp->find('ul')->wrapPHP('if(count($list) > 0):', 'endif;');
    $result = $this->qp->php();
    $this->assertRegExp('/<\?php if\(count\(\$list\) > 0\): \?>\s*<ul>/', $result);
    $this->assertRegExp('/<\/ul>\s*<\?php endif; \?>/', $result);
  }
  public function testWrapPhpEachElement() {
    $this->qp->find('ul li')->wrapPHP('if(count($list) > $i++):', 'endif;');
    $result = $this->qp->php();
    $this->assertRegExp('/<\?php if\(count\(\$list\) > \$i\+\+\): \?><li class="Odd" id="li-one">Odd<\/li><\?php endif; \?>/', $result);
    $this->assertRegExp('/<\?php if\(count\(\$list\) > \$i\+\+\): \?><li class="even" id="li-ten">Even<\/li><\?php endif; \?>/', $result);
  }
  public function testWrapAllPHP() {
    $this->qp->find('ul li')->wrapAllPHP('if(count($list) > 0):', 'endif;');
    $result = $this->qp->php();
    $this->assertRegExp('/<ul>\s*<\?php if\(count\(\$list\) > 0\): \?>/', $result);
    $this->assertRegExp('/<\?php endif; \?>\s*<\/ul>/', $result);
  }
  public function testWrapInnerPHP() {
    $this->qp->find('ul')->wrapInnerPHP('if(count($list) > 0):', 'endif;');
    $result = $this->qp->php();
    $this->assertRegExp('/<ul>\s*<\?php if\(count\(\$list\) > 0\): \?>/', $result);
    $this->assertRegExp('/<\?php endif; \?>\s*<\/ul>/', $result);
  }
  public function testWrapInnerPhpEachElement() {
    $this->qp->find('ul li')->wrapInnerPHP('if(count($list) > $i++):', 'endif;');
    $result = $this->qp->php();
    $this->assertRegExp('/<li class="Odd" id="li-one"><\?php if\(count\(\$list\) > \$i\+\+\): \?>Odd<\?php endif; \?><\/li>/', $result);
    $this->assertRegExp('/<li class="even" id="li-ten"><\?php if\(count\(\$list\) > \$i\+\+\): \?>Even<\?php endif; \?><\/li>/', $result);
  }
  public function testReplaceWithPHP() {
    $this->qp->find('#three')->replaceWithPHP('echo "Inner Text into #three"');
    $this->assertRegExp('/<div id="two" class="class-one" data-url="php:include\(0\)">\s*<\?php echo "Inner Text into #three" \?>\s*<\/div>/',$this->qp->php());
  }

}
