QPPHP
=====

PHP code block inject for QueryPath.
QueryPath is a PHP library for manipulating XML and HTML.
https://github.com/technosophos/querypath

# Install
Please refer QueryPath installation.
And copy QPPHP.php to your source tree from git repository.
At last, include copied file, like followings:

	require_once 'QueryPath/Extensions/QPPHP.php';

# Inject PHP code

Extended methods allows placing PHP code inside DOM.

* attrPHP($attr, $code) 
** inject: attr($attr, "<?php $code ?>") 
* beforePHP($code) 
** inject: before("<?php $code ?>") 
* afterPHP($code) 
** inject: after("<?php $code ?>") 
* prependPHP($code) 
** inject: prepend("<?php $code ?>") 
* appendPHP($code) 
** inject: append("<?php $code ?>") 
* wrapAllPHP($codeBefore, $codeAfter) 
** inject: wrapAll("<?php $codeBefore?><?php $codeAfter ?>") 
* wrapPHP($codeBefore, $codeAfter) 
** inject: wrap("<?php $codeBefore?><?php $codeAfter ?>") 
* wrapInnerPHP($codeBefore, $codeAfter) 
** inject: wrapInner("<?php $codeBefore?><?php $codeAfter ?>") 
* replaceWithPHP($code) 
** inject: replaceWith("<?php $code ?>")

# Output PHP code

Code injected with extended methods above won't be returned using original output methods such as html(). 
To make it work, php method have to be used.

* php() 
** inject & replace: html() 

