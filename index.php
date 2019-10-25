<?php

/*
|--------------------------------------------------------------------------
| Register The Composer Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader
| for our application. We just need to utilize it! We'll require it
| into the script here so that we do not have to worry about the
| loading of any our classes "manually". Feels great to relax.
|
 */

require __DIR__ . '/vendor/autoload.php';

$sql = <<<SQL
SELECT concat('P', `a`.`product_id`, 'V', `a`.`variant_id`, 'S', `a`.`seller_uid`) AS `id`,
       `a`.`product_title`,
       `a`.`product_sku`,
       `a`.`variant_title`,
       `a`.`variant_sku`,
       `a`.`metakey`,
       `a`.`tags`
FROM sgit.`nhbae_sellacious_cache_products` AS `a`
SQL;

use TeamTNT\TNTSearch\Stemmer\PorterStemmer;
use TeamTNT\TNTSearch\TNTSearch;

$tnt = new TNTSearch;

$tnt->loadConfig([
	'driver'    => 'mysql',
	'host'      => 'localhost',
	'database'  => 'sgit',
	'username'  => 'root',
	'password'  => '',
	'storage'   => __DIR__ . '/indexes',
	'stemmer'   => PorterStemmer::class
]);

$indexer = $tnt->createIndex('products.index.db');
$indexer->query($sql);
$indexer->setPrimaryKey('id');
$indexer->includePrimaryKey();
$indexer->run();
