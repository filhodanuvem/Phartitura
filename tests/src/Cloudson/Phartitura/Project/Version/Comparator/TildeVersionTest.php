<?php 

namespace Cloudson\Phartitura\Project\Version\Comparator;

use Cloudson\Phartitura\Project\Version\Version;

class TildeVersionTest extends \PHPUnit_Framework_TestCase
{
    /**
    * @test
    * @dataProvider getVersions
    */ 
    public function should_check_that_a_version_match_with_a_rule($vs1, $versionRule)
    {
        $v1 = new Version($vs1);

        $comparator = new TildeVersion;

        $this->assertTrue($comparator->compare($v1, $versionRule));
    }

    public function getVersions()
    {
        return [
            ['1.0.3', '~1.0'],
            ['v1.0', '~1.0'],
            ['1.2.3', '~1.2'],
            ['1.2.5', '~1.2.5'],
            ['1.2.46', '~1.2.5'],
        ];
    }

    /**
    * @test
    * @dataProvider getRulesConvertions
    */ 
    public function should_convert_tild_to_range_version_comparator($rule1, $rule2)
    {
        $comparator = new TildeVersion;
        $reflectt = new \ReflectionClass($comparator);

        $method = $reflectt->getMethod('convertToRange');
        $found = $method->invoke($comparator, $rule1);

        $this->assertEquals($rule2, $found);
    }

    public function getRulesConvertions()
    {
        return [
            ['~1.0', '>=1.0,<1.1.0'],
            ['~2.3', '>=2.3,<2.4.0'],
            ['~42.30.9', '>=42.30.9,<42.31.0'],
            ['~2.0.3', '>=2.0.3,<2.1.0'],
        ];
    }
}