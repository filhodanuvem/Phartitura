<?php

namespace Cloudson\Phartitura\Project\Version\Comparator;

use Cloudson\Phartitura\Project\Version\ComparatorStrategyInterface;
use Cloudson\Phartitura\Project\Version\Version;

class RangeVersion implements ComparatorStrategyInterface
{

    const PATTERN = '/^(>|>=|<|<=)([0-9]+(\.[0-9]+){0,2})(,(>|>=|<|<=)([0-9]+(\.[0-9]+){0,1})){0,2}$/';
    const T_GREATER = '>';
    const T_GREATER_EQUAL = '>=';
    const T_LESS = '<';
    const T_LESS_EQUAL = '<=';

    public static $compareMethods = [
        self::T_GREATER => 'compareGreater',
        self::T_GREATER_EQUAL => 'compareGreaterEqual', 
        self::T_LESS => 'compareLess',
        self::T_LESS_EQUAL => 'compareLessEqual',
    ];

    private function parse($source)
    {
        $source = str_replace(' ', '', $source);
        $matches = array();
        
        preg_match_all(self::PATTERN, $source, $matches);
        if (!$matches || !$matches[0]) {
            return array();
        }

        return [
            $matches[1][0],  
            $matches[2][0],
            count($matches) > 5 ? $matches[5][0] : null,
            count($matches) > 6 ? $matches[6][0] : null,
        ];
    }

    public function compare(Version $versionCurrent, $versionRule)
    {
        $lexemes = $this->parse($versionRule);
        if (!$lexemes) {

        }

        if (array_key_exists($lexemes[0], static::$compareMethods)) {

        }

        $result = true;
        for ($i = 0; $i < count($lexemes); $i += 2) {
            $operator = $lexemes[$i];
            if (!$operator) {
                continue;
            }
            $compareMehod = static::$compareMethods[$operator];    
            $result = $result && $this->$compareMehod($versionCurrent, $lexemes[$i + 1]);
        }
        
        return $result;
    }


    private function compareGreater(Version $version, $versionLimit) 
    {
        return version_compare((string) $version, $versionLimit)  == 1;
    }   

    private function compareGreaterEqual(Version $version, $versionLimit) 
    {
        return version_compare((string) $version, $versionLimit)  >= 0 ;
    }

    private function compareLess(Version $version, $versionLimit) 
    {
        return version_compare((string) $version, $versionLimit)  == -1 ;
    }    

    private function compareLessEqual(Version $version, $versionLimit) 
    {
        return version_compare((string) $version, $versionLimit)  <= 0 ;
    }
}