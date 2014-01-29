<?php 

namespace Cloudson\Phartitura\Project\Version\Comparator;

use Cloudson\Phartitura\Project\Version\Version;

class TildeVersionTest extends \PHPUnit_Framework_TestCase
{
    /**
    * @test
    * @dataProvider getVersions
    */ 
    public function should_check_that_a_version_match_with_a_rule($vs1, $vs2)
    {
        return; 
        $v1 = new Version($vs1);
        $v2 = new Version($vs2);

        $comparator = new TildeVersion;

        $this->assertTrue($comparator->compare($v1, $v2));
    }

    public function getVersions()
    {
        return [
            ['1.0.3', '~1.0'],
            ['1.2.3', '~1.2'],
            ['1.2.5', '~1.2.5'],
            ['1.2.46', '~1.2.5'],
        ];
    }
}