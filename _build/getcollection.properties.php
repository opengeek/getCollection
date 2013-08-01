<?php
/**
 * Default properties for getCollection snippet
 *
 * @package getcollection
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'key',
        'desc' => 'The placeholder key in which to store the collection. (required)',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'class',
        'desc' => 'The xPDOObject class to query the collection from. (required)',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'where',
        'desc' => 'A JSON expression of criteria to build any additional where clauses from, e.g. &where=`{{"alias:LIKE":"foo%", "OR:alias:LIKE":"%bar"},{"OR:pagetitle:=":"foobar", "AND:description:=":"raboof"}}`',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'sortby',
        'desc' => 'A field name to sort by or JSON object of field names and sortdir for each field, e.g. {"publishedon":"ASC","createdon":"DESC"}. Defaults to empty.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'sortbyAlias',
        'desc' => 'Query alias for sortby field. Defaults to an empty string.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'sortbyEscaped',
        'desc' => 'Determines if the field name specified in sortby should be escaped. Defaults to 0.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => false,
    ),
    array(
        'name' => 'sortdir',
        'desc' => 'Order which to sort by. Defaults to DESC.',
        'type' => 'list',
        'options' => array(
            array('text' => 'ASC','value' => 'ASC'),
            array('text' => 'DESC','value' => 'DESC'),
        ),
        'value' => 'DESC',
    ),
    array(
        'name' => 'limit',
        'desc' => 'Limits the number of resources returned. Defaults to 5.',
        'type' => 'textfield',
        'options' => '',
        'value' => '10',
    ),
    array(
        'name' => 'offset',
        'desc' => 'An offset of resources returned by the criteria to skip.',
        'type' => 'textfield',
        'options' => '',
        'value' => '0',
    ),
    array(
        'name' => 'totalVar',
        'desc' => 'The name of a placeholder to set with the total records that would be selected by the where without the limit applied.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'total',
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
