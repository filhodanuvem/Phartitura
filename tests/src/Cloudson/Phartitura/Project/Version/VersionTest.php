<?php

namespace Cloudson\Phartitura\Project\Version;

class VersionTest extends \PHPUnit_Framework_TestCase
{
    /**
    * @test
    * @dataProvider get_versions_using_semver
    * @see http://semver.org 
    */ 
    public function check_if_version_uses_semver($versionString)
    {
        $v = new Version($versionString);

        $this->assertTrue($v->isSemVer());
    }

    /**
    * @test 
    * @dataProvider get_versions_using_semver
    */ 
    public function should_uses_metadata_on_semver($versionString, $major, $minor, $patch)
    {
        $v = new Version($versionString);

        $this->assertEquals($major, $v->getMajor());
        $this->assertEquals($minor, $v->getMinor());
        $this->assertEquals($patch, $v->getPatch());
    }

    /**
    * @test 
    */ 
    public function should_have_created_at()
    {
        $v = new Version('1.42.0');
        $v->setCreatedAt(new \DateTime('2014-01-01'));
    }

    /**
    * @test
    * @expectedException \OutOfRangeException
    */ 
    public function should_not_have_created_at_future()
    {
        $v = new Version('1.42.0');
        $v->setCreatedAt(new \DateTime('+1day'));
    }

    
    public function get_versions_using_semver()
    {
        return [
            ['1.42.0', '1', '42', '0'],
            ['0.0.1a' , 0, 0, '1'],
            ['v1.300.3', '1', 300 , 3],
        ];
    }

    /**
    * @test
    * @dataProvider provide_slugfies
    */ 
    public function should_slugfy($versionString, $expected)
    {
        $version = new Version($versionString);

        $this->assertEquals($expected, $version->getSlugfy());
    }

    public function provide_slugfies()
    {
        return [
            ['1.40.0', '1-40-0'],
            ['2.0.0a', '2-0-0a'],
            // ['3.0.5-alpha8', '3-0-5-alpha8']
        ];
    }

    /**
    * @test
    * @expectedException \OutOfRangeException
    */ 
    public function should_avoid_versions_in_future()
    {
        $version = new Version('1.0.0');

        $version->setCreatedAt(new \DateTime('tomorrow'));
    }
}