<?php

namespace Cloudson\Phartitura\Project\Version\Comparator;

use Cloudson\Phartitura\Project\Version\ComparatorInterface;
use Cloudson\Phartitura\Project\Version\Version;

class RangeVersion implements ComparatorInterface
{

    const PATTERN = '/^(>|>=|<|<=|!=)([0-9]+(\.[0-9]+){0,2}([a-zA-Z]*|-dev))((,|\|)(>|>=|<|<=|!=)([0-9]+(\.[0-9]+){0,2}([a-zA-Z]*|-dev))){0,1}$/';
    const T_GREATER = '>';
    const T_GREATER_EQUAL = '>=';
    const T_LESS = '<';
    const T_LESS_EQUAL = '<=';
    const T_NOT = '!=';
    const T_OP_AND = ',';
    const T_OP_OR = '|';


    public static $compareMethods = [
        self::T_GREATER => 'compareGreater',
        self::T_GREATER_EQUAL => 'compareGreaterEqual', 
        self::T_LESS => 'compareLess',
        self::T_LESS_EQUAL => 'compareLessEqual',
        self::T_NOT => 'compareNot',
    ];

    private function parse($source)
    {
        $matches = array();
        preg_match_all(self::PATTERN, $source, $matches);

        return [
            $matches[1][0],  
            $matches[2][0],
            count($matches) > 6 ? $matches[6][0] : null,
            count($matches) > 7 ? $matches[7][0] : null,
            count($matches) > 8 ? $matches[8][0] : null,
        ];
    }

    public function compare(Version $versionCurrent, $versionRule)
    {
        $versionRule = str_replace(' ', '', $versionRule);
        if (!preg_match(self::PATTERN, $versionRule)) {
            return false;
        }

        $lexemes = $this->parse($versionRule);

        $boolOperator = $lexemes[2];
        unset($lexemes[2]);
        $lexemes = array_chunk($lexemes, 4)[0];
        $lexemesLength = count($lexemes);

        $result = true;
        for ($i = 0; $i < $lexemesLength; $i += 2) {
            $mathOperator = $lexemes[$i];
            if (!$mathOperator) {
                continue;
            }
            $compareMehod = static::$compareMethods[$mathOperator];    
            if ($boolOperator == self::T_OP_OR) {
                $result = $result || $this->$compareMehod($versionCurrent, $lexemes[$i + 1]);
                continue;
            }
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

    private function compareNot(Version $version, $versionLimit)
    {
        return version_compare((string) $version, $versionLimit) != 0;
    }

}