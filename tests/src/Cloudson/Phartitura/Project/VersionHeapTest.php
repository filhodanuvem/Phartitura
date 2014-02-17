<?php

namespace Cloudson\Phartitura\Project;

class VersionHeapTest extends \PHPUnit_Framework_TestCase
{
    /**
    * @test
    */ 
    public function should_test_if_last_version_is_devmaster()
    {
        $heap = new VersionHeap;
        $heap->insert(['version' => 'dev-master', 'time' => '2013-09-30 00:00:00']);
        $heap->insert(['version' => '1.0.0', 'time' => '2013-09-01 00:00:00']);
        $heap->insert(['version' => '2.0.0', 'time' => '2013-09-03 00:00:00']);

        $version = $this->bottom($heap);

        $this->assertEquals('dev-master', $version['version']);

        $heap = new VersionHeap;
        $heap->insert(['version' => '1.0.0', 'time' => '2013-09-01 00:00:00']);
        $heap->insert(['version' => 'dev-master', 'time' => '2013-09-30 00:00:00']);
        $heap->insert(['version' => '2.0.0', 'time' => '2013-09-03 00:00:00']);

        $version = $this->bottom($heap);

        $this->assertEquals('dev-master', $version['version']);
    }

    /**
    * @test
    */ 
    public function should_test_if_priority_by_timestamp_is_correct()
    {
        $heap = new VersionHeap;
        $heap->insert(['version' => 'dev-master', 'time' => '2013-09-30 00:00:00']);
        $heap->insert(['version' => '1.0.0', 'time' => '2013-09-01 00:00:00']);
        $heap->insert(['version' => '2.0.0', 'time' => '2013-09-03 00:00:00']);

        $version = $heap->current();

        $this->assertEquals('2.0.0', $version['version']);
    }

    /**
    * @test
    */ 
    public function should_test_if_priority_by_tag_is_correct()
    {
        $heap = new VersionHeap;
        $heap->insert(['version' => 'dev-master', 'time' => '2013-09-01 00:00:00']);
        $heap->insert(['version' => '2.0.0', 'time' => '2013-09-01 00:00:00']);
        $heap->insert(['version' => '1.0.0', 'time' => '2013-09-01 00:00:00']);

        $version = $heap->current();

        $this->assertEquals('2.0.0', $version['version']);
    }


    private function bottom(VersionHeap $heap)
    {   
        foreach ($heap as $v) {}

        return $v;
    }
}