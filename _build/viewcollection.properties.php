<?php
/**
 * Default properties for viewCollection snippet
 *
 * @package getcollection
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'key',
        'desc' => 'The placeholder key to get the object collection from. (required)',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'tpl',
        'desc' => 'Name of a chunk serving as an object template. NOTE: if not provided, properties are dumped to output for each object.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'tplOdd',
        'desc' => 'Name of a chunk serving as object template for objects with an odd idx value (see idx property).',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'tplFirst',
        'desc' => 'Name of a chunk serving as object template for the first object (see first property).',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'tplLast',
        'desc' => 'Name of a chunk serving as object template for the last object (see last property).',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'tplWrapper',
        'desc' => 'Name of a chunk serving as wrapper template for the Snippet output. This does not work with toSeparatePlaceholders.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'wrapIfEmpty',
        'desc' => 'Indicates if empty output should be wrapped by the tplWrapper, if specified. Defaults to false.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => false,
    ),
    array(
        'name' => 'idx',
        'desc' => 'You can define the starting idx of the collection, which is an property that is incremented as each object is rendered. Default is 1.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'first',
        'desc' => 'Define the idx which represents the first object (see tplFirst). Defaults to 1.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'last',
        'desc' => 'Define the idx which represents the last object (see tplLast). Defaults to the number of objects being summarized + first - 1',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'toPlaceholder',
        'desc' => 'If set, will assign the result to this placeholder instead of outputting it directly.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'toSeparatePlaceholders',
        'desc' => 'If set, will assign EACH result to a separate placeholder named by this param suffixed with a sequential number (starting from 0).',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'debug',
        'desc' => 'If true, will send the SQL query to the MODX log. Defaults to false.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => false,
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
