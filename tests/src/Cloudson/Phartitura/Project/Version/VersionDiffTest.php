<?php

namespace Cloudson\Phartitura\Project\Version; 

class VersionDiffTest extends \PHPUnit_Framework_TestCase
{
    /**
    * @test
    **/ 
    public function should_compare_two_equal_versions_()
    {
        $versionDiff = new VersionDiff(new Version('2.3.2'), new Version('2.3.2'));
        $versionDiff->areEquals();
    }

    /**
    * @test
    **/ 
    public function should_be_transvarsable()
    {
        $versionDiff = new VersionDiff(new Version('2.3.2'), new Version('3.2.4'));
        $this->assertEquals([
            '2.3.2', '3.2.4'
        ],  iterator_to_array($versionDiff->getIterator()));
    }

    /**
    * @test
    * @dataProvider provider_equals_versions
    */ 
    public function should_be_callable($versionString1, $versionString2)
    {
        $version1 = new Version($versionString1);
        $version2 = new Version($versionString2);

        $versionDiff = new VersionDiff();
        
        $this->assertFalse($versionDiff($version1, $version2)); 
    }

    public function provider_equals_versions()
    {
        return [
            ['1.0.0', '1.0.0  '],
            ['2.3.0', '2.3.0']
        ];
    }

}