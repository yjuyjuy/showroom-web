<?php

$finder = PhpCsFixer\Finder::create()
	->notPath('src/Symfony/Component/Translation/Tests/fixtures/resources.php')
	->in(__DIR__)
;

return PhpCsFixer\Config::create()
	->setRules([
		'@PSR2' => true,
	])
	->setIndent("\t")
	->setUsingCache(true)
	->setFinder($finder)
;
