<?php
/**
 * getService
 *
 * Get a MODx service.
 *
 * @author Jason Coward
 * @copyright Copyright 2013, Jason Coward
 *
 * name - The service name
 * class - The service class name
 * path - The path to the service class
 *
 * @var modX $modx
 * @var array $scriptProperties
 */
$oldTarget = null;
if (!empty($verbose)) {
    $oldTarget = $modx->setLogTarget('HTML');
}
if (empty($class)) {
    $modx->log(modX::LOG_LEVEL_ERROR, "No class property specified", '', 'getService', __FILE__, __LINE__);
    if (!empty($oldTarget)) $modx->setLogTarget($oldTarget);
    return '';
}
if (empty($name)) {
    $modx->log(modX::LOG_LEVEL_ERROR, "No name property specified", '', 'getService', __FILE__, __LINE__);
    if (!empty($oldTarget)) $modx->setLogTarget($oldTarget);
    return '';
}
if (empty($path)) {
    $path = $modx->getOption("{$name}.core_path", $scriptProperties, MODX_CORE_PATH . "components/{$name}/") . "model/{$name}/";
}

unset($scriptProperties['class'], $scriptProperties['name'], $scriptProperties['path']);

$modx->getService($name, $class, $path, $scriptProperties);

if (!empty($oldTarget)) $modx->setLogTarget($oldTarget);
return '';
