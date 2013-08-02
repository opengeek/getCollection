<?php
/**
 * Default properties for getService snippet
 *
 * @package getcollection
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'name',
        'desc' => 'The name of the service. (required)',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'class',
        'desc' => 'The class name of the service. (required)',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'path',
        'desc' => 'The path to the service class. If not specified, attempts to use the name of the component to build the path.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'verbose',
        'desc' => 'If true, will send the MODX log output to screen for the duration of the Snippet processing. Defaults to false.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => false,
    ),
);

return $properties;
