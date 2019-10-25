<?php


require __DIR__ . '/vendor/autoload.php';

use TeamTNT\TNTSearch\Stemmer\PorterStemmer;
use TeamTNT\TNTSearch\TNTSearch;

try
{
	$keyword = @$argv[1];

	if (!$keyword)
	{
		throw new Exception('Empty search not allowed');
	}

	$tnt = new TNTSearch;

	$start = microtime(true);

	$tnt->loadConfig([
		'driver'    => 'mysql',
		'host'      => 'localhost',
		'database'  => 'sgit',
		'username'  => 'root',
		'password'  => '',
		'storage'   => __DIR__ . '/indexes',
		'stemmer'   => PorterStemmer::class
	]);

	$tnt->selectIndex('products.index.db');
	$tnt->asYouType = true;

	$results = $tnt->search($keyword);

	$end = microtime(true);

	echo sprintf('Total %d results found for "%s" in %f seconds.', $results['hits'], $keyword, $end - $start);

	print_r($results);
}
catch (\Exception $e)
{
	echo $e->getMessage();
	echo PHP_EOL;

	exit;
}
