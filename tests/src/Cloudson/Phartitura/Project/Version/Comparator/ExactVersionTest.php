<?php

namespace Cloudson\Phartitura\Project\Version\Comparator; 

use Cloudson\Phartitura\Project\Version\Version;

class ExactVersionTest extends \PHPUnit_Framework_TestCase
{
    /**
    * @test
    * @dataProvider getEqualsVersions
    */ 
    public function should_check_that_two_versions_are_equals($vs1, $vs2)
    {
        $v1 = new Version($vs1);
        $v2 = new Version($vs2);

        $comparator = new ExactVersion;

        $this->assertTrue($comparator->compare($v1, $v2));
    }

    /**
    * @test
    * @dataProvider getNotEqualsVersions
    */ 
    public function  should_check_that_two_versions_are_not_equals($vs1, $vs2)
    {
        $v1 = new Version($vs1);
        $v2 = new Version($vs2);

        $comparator = new ExactVersion;

        $this->assertFalse($comparator->compare($v1, $v2));
    }

    public function getEqualsVersions()
    {
        return [
            ['1.0.0', ' 1.0.0'],
            ['2.3.0 ', ' 2.3.0'],
        ];
    }

    public function getNotEqualsVersions()
    {
        return [
            ['1.0.0', ' 1.0.1'],
            ['2.3.0 ', ' 2.3.0a'],
        ];
    }
}