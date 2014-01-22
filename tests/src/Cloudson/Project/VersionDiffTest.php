<?php

namespace Cloudson\Phartitura\Project; 

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

}