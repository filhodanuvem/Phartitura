<?php

namespace Cloudson\Phartitura\Project\Version\Comparator;

use Cloudson\Phartitura\Project\Version\Version;

class RangeVersionTest extends \PHPUnit_Framework_TestCase
{
    /**
    * @test
    * @dataProvider getRules
    */ 
    public function should_parse_and_generate_versions_limit($string, $expected)
    {
        $comparator = new RangeVersion;

        $reflect = new \ReflectionClass($comparator);
        $method = $reflect->getMethod('parse');
        $method->setAccessible(true);

        $versions = $method->invoke($comparator, $string);

        $this->assertEquals($expected, $versions);
    }

    public function getRules()
    {
        return [
            ['>1.0', [RangeVersion::T_GREATER, '1.0', null, null]],
            ['>= 1.0', [RangeVersion::T_GREATER_EQUAL, '1.0', null, null]],
            ['>= 1.0, <3', [RangeVersion::T_GREATER_EQUAL, '1.0', RangeVersion::T_LESS, '3']],
            ['>= 3.2.3, <3.3', [RangeVersion::T_GREATER_EQUAL, '3.2.3', RangeVersion::T_LESS, '3.3']],
            ['>= 3.0.3, <=4.0.2', [RangeVersion::T_GREATER_EQUAL, '3.0.3', RangeVersion::T_LESS_EQUAL, '4.0.2']],
        ];
    }

    /**
    * @test
    * @dataProvider getRulesWithBottomLimit
    */ 
    public function should_test_range_with_bottom_limit($version, $rule)
    {
        $comparator = new RangeVersion;
        $found = $comparator->compare($version, $rule);

        $this->assertTrue($found);
    }

    public function getRulesWithBottomLimit()
    {
        return [
            [new Version('2.0'), '>1.0'],
            [new Version('2.3.2'), '>=2.2'],
            [new Version('2.2'), '>1, <=2.2'],
            [new Version('2.3'), '>2,<=2.4'],
        ];
    }

    /**
    * @test
    * @dataProvider getRulesWithTopLimit
    */ 
    public function should_test_range_with_top_limit($version, $rule)
    {
        $comparator = new RangeVersion;
        $found = $comparator->compare($version, $rule);

        $this->assertTrue($found);
    }

    public function getRulesWithTopLimit()
    {
        return [
            [new Version('5.2.41'), '<5.2.43'],
            [new Version('2.1.2'), '<=2.2'],
            [new Version('2.2'), '>1.2, <=2.2'],
            [new Version('2.3'), '>2,<2.4'],
        ];
    }

    /**
    * @test
    * @dataProvider getRulesThatGeneratesErrors
    */
    public function should_test_errors_with_range_versions($version, $rule)
    {
        $comparator = new RangeVersion;
        $found = $comparator->compare($version, $rule);

        $this->assertFalse($found);
    }

    public function getRulesThatGeneratesErrors()
    {
        return [
            [new Version('5.3'), '<5.2.43'],
            [new Version('2.3.1'), '<=2.2'],
            [new Version('2.2'), '>1.2, <2.2'],
            [new Version('3'), '>2,<2.4'],
        ];
    }
}