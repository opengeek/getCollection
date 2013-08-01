<?php
/**
 * getCollection
 *
 * Get an array of xPDOObjects and store it to a placeholder for reuse.
 *
 * @author Jason Coward
 * @copyright Copyright 2013, Jason Coward
 *
 * SELECTION
 *
 * class - The class to query
 * key - The key of the placeholder to set the collection in
 * fields - (Opt) A comma-delimited list of field names to include/exclude (see excludeFields)
 * excludeFields - (Opt) Use fields as exclusions instead of inclusions [default=0]
 * where - (Opt) A JSON expression of criteria to build the where clause from. An example for class=modResource would be
 *   &where=`{{"alias:LIKE":"foo%", "OR:alias:LIKE":"%bar"},{"OR:pagetitle:=":"foobar", "AND:description:=":"raboof"}}`
 *
 * sortby - (Opt) Field to sort by or a JSON array, e.g. {"publishedon":"ASC","createdon":"DESC"} [default=]
 * sortbyAlias - (Opt) Query alias for sortby field [default=]
 * sortbyEscaped - (Opt) Escapes the field name(s) specified in sortby [default=0]
 * sortdir - (Opt) Order which to sort by [default=DESC]
 * limit - (Opt) Limits the number of resources returned [default=10]
 * offset - (Opt) An offset of resources returned by the criteria to skip [default=0]
 *
 */
$oldTarget = null;
if (!empty($verbose)) {
    $oldTarget = $modx->setLogTarget('HTML');
}
if (empty($class)) {
    $modx->log(modX::LOG_LEVEL_ERROR, "No class property specified", '', 'getCollection', __FILE__, __LINE__);
    if (!empty($oldTarget)) $modx->setLogTarget($oldTarget);
    return '';
}
if (empty($key)) {
    $modx->log(modX::LOG_LEVEL_ERROR, "No key property specified", '', 'getCollection', __FILE__, __LINE__);
    if (!empty($oldTarget)) $modx->setLogTarget($oldTarget);
    return '';
}

if (!empty($where)) {
    if (is_string($where) && (strpos($where, '{') !== false || strpos($where, '[') !== false)) $where = $modx->fromJSON($where);
} else {
    $where = array();
}

if (!empty($fields)) {
    if (!is_array($fields)) $fields = explode(',', $fields);
    array_walk($fields, 'trim');
} else {
    $fields = array();
}
$excludeFields = !empty($excludeFields) ? true : false;

$sortby = isset($sortby) ? $sortby : '';
$sortbyAlias = isset($sortbyAlias) ? $sortbyAlias : $class;
$sortbyEscaped = !empty($sortbyEscaped) ? true : false;
$sortdir = isset($sortdir) ? $sortdir : 'DESC';
$limit = isset($limit) ? (integer) $limit : 10;
$offset = isset($offset) ? (integer) $offset : 0;
$totalVar = !empty($totalVar) ? $totalVar : 'total';

/* build query */
$criteria = $modx->newQuery($class, $where);

/* include/exclude fields */
$columns = $modx->getSelectColumns($class, '', '', $fields, $excludeFields);
$criteria->select($columns);

$total = $modx->getCount($class, $criteria);
$modx->setPlaceholder($totalVar, $total);

/* add sorting */
if (!empty($sortby)) {
    if (strpos($sortby, '{') === 0) {
        $sortby = $modx->fromJSON($sortby);
    } elseif (!is_array($sortby)) {
        $sortby = array($sortby => $sortdir);
    }
    if (is_array($sortby)) {
        while (list($sort, $dir) = each($sortby)) {
            if ($sortbyEscaped) $sort = $modx->escape($sort);
            if (!empty($sortbyAlias)) {
                if ($sortbyEscaped) $sortbyAlias = $modx->escape($sortbyAlias);
                $sort = $sortbyAlias . ".{$sort}";
            }
            $criteria->sortby($sort, $dir);
        }
    }
}
if (!empty($limit)) $criteria->limit($limit, $offset);

if (!empty($debug)) {
    $criteria->prepare();
    $modx->log(modX::LOG_LEVEL_ERROR, $criteria->toSQL());
}
$modx->setPlaceholder($key, $modx->getCollection($class, $criteria));

if (!empty($oldTarget)) $modx->setLogTarget($oldTarget);
return '';
