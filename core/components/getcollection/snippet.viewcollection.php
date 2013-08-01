<?php
/**
 * viewCollection
 *
 * Iterate an array of xPDOObjects from a placeholder and render the properties of each object with a Chunk.
 *
 * @author Jason Coward
 * @copyright Copyright 2013, Jason Coward
 *
 * SELECTION
 *
 * key - The key of the placeholder to get the object collection from
 *
 * TEMPLATES
 *
 * tpl - Name of a chunk serving as an object template
 * [NOTE: if not provided, properties are dumped to output for each object]
 *
 * tplOdd - (Opt) Name of a chunk serving as object template for objects with an odd idx value
 * (see idx property)
 * tplFirst - (Opt) Name of a chunk serving as object template for the first object (see first
 * property)
 * tplLast - (Opt) Name of a chunk serving as object template for the last object (see last
 * property)
 * tpl_{n} - (Opt) Name of a chunk serving as object template for the nth object
 *
 * tplWrapper - (Opt) Name of a chunk serving as a wrapper template for the output
 * [NOTE: Does not work with toSeparatePlaceholders]
 *
 * OPTIONS
 *
 * idx - (Opt) You can define the starting idx of the objects, which is an property that is
 * incremented as each object is rendered [default=1]
 * first - (Opt) Define the idx which represents the first object (see tplFirst) [default=1]
 * last - (Opt) Define the idx which represents the last object (see tplLast) [default=# of
 * objects being summarized + first - 1]
 * outputSeparator - (Opt) An optional string to separate each tpl instance [default="\n"]
 * wrapIfEmpty - (Opt) Indicates if the tplWrapper should be applied if the output is empty [default=0]
 *
 * @var modX $modx
 * @var array $scriptProperties
 */
if (!function_exists('getDivisors')) {
    function getDivisors($integer) {
        $divisors = array();
        for ($i = $integer; $i > 1; $i--) {
            if (($integer % $i) === 0) {
                $divisors[] = $i;
            }
        }
        return $divisors;
    }
}

$oldTarget = null;
if (!empty($verbose)) {
    $oldTarget = $modx->setLogTarget('HTML');
}
if (empty($key)) {
    $modx->log(modX::LOG_LEVEL_ERROR, "No key property specified", '', 'viewCollection', __FILE__, __LINE__);
    if (!empty($oldTarget)) $modx->setLogTarget($oldTarget);
    return '';
}

$output = array();
$outputSeparator = isset($outputSeparator) ? $outputSeparator : "\n";

$collection = $modx->getPlaceholder($key);
if (is_array($collection)) {

    $idx = !empty($idx) && $idx !== '0' ? (integer) $idx : 1;
    $first = empty($first) && $first !== '0' ? 1 : (integer) $first;
    $last = empty($last) ? (count($collection) + $idx - 1) : (integer) $last;

    foreach ($collection as $id => $object) {
        $odd = ($idx & 1);
        $properties = array_merge(
            $scriptProperties
            ,array(
                'idx' => $idx
            ,'first' => $first
            ,'last' => $last
            ,'odd' => $odd
            )
            ,$object->get($fields)
        );
        $objectTpl = false;
        if ($idx == $first && !empty($tplFirst)) {
            $objectTpl = $modx->getChunk($tplFirst, $properties);
        }
        if ($idx == $last && empty($objectTpl) && !empty($tplLast)) {
            $objectTpl = $modx->getChunk($tplLast, $properties);
        }
        $tplidx = 'tpl_' . $idx;
        if (empty($objectTpl) && !empty($$tplidx)) {
            $objectTpl = $modx->getChunk($$tplidx, $properties);
        }
        if ($idx > 1 && empty($objectTpl)) {
            $divisors = getDivisors($idx);
            if (!empty($divisors)) {
                foreach ($divisors as $divisor) {
                    $tplnth = 'tpl_n' . $divisor;
                    if (!empty($$tplnth)) {
                        $objectTpl = $modx->getChunk($$tplnth, $properties);
                        if (!empty($objectTpl)) {
                            break;
                        }
                    }
                }
            }
        }
        if ($odd && empty($objectTpl) && !empty($tplOdd)) {
            $objectTpl = $modx->getChunk($tplOdd, $properties);
        }
        if (!empty($tplCondition) && !empty($conditionalTpls) && empty($objectTpl)) {
            $conTpls = $modx->fromJSON($conditionalTpls);
            $subject = $properties[$tplCondition];
            $tplOperator = !empty($tplOperator) ? $tplOperator : '=';
            $tplOperator = strtolower($tplOperator);
            $tplCon = '';
            foreach ($conTpls as $operand => $conditionalTpl) {
                switch ($tplOperator) {
                    case '!=':
                    case 'neq':
                    case 'not':
                    case 'isnot':
                    case 'isnt':
                    case 'unequal':
                    case 'notequal':
                        $tplCon = (($subject != $operand) ? $conditionalTpl : $tplCon);
                        break;
                    case '<':
                    case 'lt':
                    case 'less':
                    case 'lessthan':
                        $tplCon = (($subject < $operand) ? $conditionalTpl : $tplCon);
                        break;
                    case '>':
                    case 'gt':
                    case 'greater':
                    case 'greaterthan':
                        $tplCon = (($subject > $operand) ? $conditionalTpl : $tplCon);
                        break;
                    case '<=':
                    case 'lte':
                    case 'lessthanequals':
                    case 'lessthanorequalto':
                        $tplCon = (($subject <= $operand) ? $conditionalTpl : $tplCon);
                        break;
                    case '>=':
                    case 'gte':
                    case 'greaterthanequals':
                    case 'greaterthanequalto':
                        $tplCon = (($subject >= $operand) ? $conditionalTpl : $tplCon);
                        break;
                    case 'isempty':
                    case 'empty':
                        $tplCon = empty($subject) ? $conditionalTpl : $tplCon;
                        break;
                    case '!empty':
                    case 'notempty':
                    case 'isnotempty':
                        $tplCon = !empty($subject) && $subject != '' ? $conditionalTpl : $tplCon;
                        break;
                    case 'isnull':
                    case 'null':
                        $tplCon = $subject == null || strtolower($subject) == 'null' ? $conditionalTpl : $tplCon;
                        break;
                    case 'inarray':
                    case 'in_array':
                    case 'ia':
                        $operand = explode(',', $operand);
                        $tplCon = in_array($subject, $operand) ? $conditionalTpl : $tplCon;
                        break;
                    case 'between':
                    case 'range':
                    case '>=<':
                    case '><':
                        $operand = explode(',', $operand);
                        $tplCon = ($subject >= min($operand) && $subject <= max($operand)) ? $conditionalTpl : $tplCon;
                        break;
                    case '==':
                    case '=':
                    case 'eq':
                    case 'is':
                    case 'equal':
                    case 'equals':
                    case 'equalto':
                    default:
                        $tplCon = (($subject == $operand) ? $conditionalTpl : $tplCon);
                        break;
                }
            }
            if (!empty($tplCon)) {
                $objectTpl = $modx->getChunk($tplCon, $properties);
            }
        }
        if (!empty($tpl) && empty($objectTpl)) {
            $objectTpl = $modx->getChunk($tpl, $properties);
        }
        if ($objectTpl === false && !empty($debug)) {
            $chunk = $modx->newObject('modChunk');
            $chunk->setCacheable(false);
            $output[]= $chunk->process(array(), '<pre>' . print_r($properties, true) .'</pre>');
        } else {
            $output[]= $objectTpl;
        }
        $idx++;
    }
}

if (!empty($oldTarget)) $modx->setLogTarget($oldTarget);
return $output;
