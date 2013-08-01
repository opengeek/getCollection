<?php
/**
 * getCollection
 *
 * @package getCollection
 * @author Jason Coward <jason@modx.com>
 */
$tstart = microtime(true);
set_time_limit(0);

/* define sources */
$root = dirname(dirname(__FILE__)) . '/';
$sources= array (
    'root' => $root,
    'build' => $root . '_build/',
    'lexicon' => $root . '_build/lexicon/',
    'source_core' => $root . 'core/components/getcollection',
);
unset($root);

/* instantiate MODx */
require_once $sources['build'].'build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx= new modX();
$modx->initialize('mgr');
$modx->setLogLevel(xPDO::LOG_LEVEL_INFO);
$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');

/* set package info */
define('PKG_NAME','getcollection');
define('PKG_VERSION','1.0.0');
define('PKG_RELEASE','beta');

/* load builder */
$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage(PKG_NAME, PKG_VERSION, PKG_RELEASE);
//$builder->registerNamespace('getcollection',false,true,'{core_path}components/getcollection/');

/* create snippet objects */
$modx->log(xPDO::LOG_LEVEL_INFO,'Adding in snippets.'); flush();
$snippet= $modx->newObject('modSnippet');
$snippet->set('name', 'getCollection');
$snippet->set('description', '<strong>'.PKG_VERSION.'-'.PKG_RELEASE.'</strong> A general purpose snippet for retrieving data collections and storing them in a placeholder for reuse on a request.');
$snippet->set('category', 0);
$snippet->set('snippet', file_get_contents($sources['source_core'] . '/snippet.getcollection.php'));
$properties = include $sources['build'].'getcollection.properties.php';
$snippet->setProperties($properties);
/* create a transport vehicle for the data object */
$vehicle = $builder->createVehicle(
    $snippet,
    array(
        xPDOTransport::PRESERVE_KEYS => false,
        xPDOTransport::UPDATE_OBJECT => true,
        xPDOTransport::UNIQUE_KEY => 'name',
    )
);
$vehicle->resolve(
    'file',
    array(
        'source' => $sources['source_core'],
        'target' => "return MODX_CORE_PATH . 'components/';",
    )
);
$builder->putVehicle($vehicle);
unset($snippet, $properties);

$snippet= $modx->newObject('modSnippet');
$snippet->set('name', 'viewCollection');
$snippet->set('description', '<strong>'.PKG_VERSION.'-'.PKG_RELEASE.'</strong> A general purpose snippet for iterating data collections stored in a placeholder and rendering their properties via chunks.');
$snippet->set('category', 0);
$snippet->set('snippet', file_get_contents($sources['source_core'] . '/snippet.viewcollection.php'));
$properties = include $sources['build'].'viewcollection.properties.php';
$snippet->setProperties($properties);
/* create a transport vehicle for the data object */
$vehicle = $builder->createVehicle(
    $snippet,
    array(
        xPDOTransport::PRESERVE_KEYS => false,
        xPDOTransport::UPDATE_OBJECT => true,
        xPDOTransport::UNIQUE_KEY => 'name',
    )
);
$builder->putVehicle($vehicle);
unset($snippet, $properties);

/* now pack in the license file, readme and setup options */
$builder->setPackageAttributes(array(
    'license' => file_get_contents($sources['source_core'] . '/docs/license.txt'),
    'readme' => file_get_contents($sources['source_core'] . '/docs/readme.txt'),
    'changelog' => file_get_contents($sources['source_core'] . '/docs/changelog.txt'),
));

/* zip up the package */
$builder->pack();

$tend= microtime(true);
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

$modx->log(xPDO::LOG_LEVEL_INFO, "Package Built.");
$modx->log(xPDO::LOG_LEVEL_INFO, "Execution time: {$totalTime}");
exit();
