<?php

namespace Cloudson\Phartitura\Project\Version\Comparator\Decorator;

use Cloudson\Phartitura\Project\Version\Comparator;
use Cloudson\Phartitura\Project\Version\Version;
use Cloudson\Phartitura\Project\Project;
use Cloudson\Phartitura\Project\VersionHeap;

class AddStableRuleTest extends \PHPUnit_Framework_TestCase
{

    /**
    * @test
    */ 
    public function should_add_comparator_discovering_stable_version()
    {
        $comparator = new Comparator;
        $heap = new VersionHeap;
        $heap->insert([
            'version' => '2.0.0',
            'time' => '2014-01-01 00:00:00'
        ]);
        $heap->insert([
            'version' => '2.3.0',
            'time' => '2014-01-02 00:00:00'
        ]);
        $heap->insert([
            'version' => '2.3.2',
            'time' => '2014-01-05 00:00:00'
        ]);
        $heap->insert([
            'version' => '2.3.2-dev',
            'time' => '2014-01-05 00:00:00'
        ]);

        $addStableRule = new AddStableRule($heap);
        $addStableRule($comparator);

        $this->assertTrue($comparator->compare(new Version('2.3.2'), '@stable'));
    }

    /**
    * @test
    */ 
    public function should_returns_false_if_not_there_versions()
    {
        $comparator = new Comparator;
        $heap = new VersionHeap;

        $addStableRule = new AddStableRule($heap);
        $addStableRule($comparator);

        $this->assertFalse($comparator->compare(new Version('2.3.2'), '@stable'));
    }
}