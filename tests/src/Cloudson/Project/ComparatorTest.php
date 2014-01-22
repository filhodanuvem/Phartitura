<?php

namespace Cloudson\Phartitura\Project;

class ComparatorTest extends \PHPUnit_Framework_TestCase
{
    /**
    * @test
    */ 
    public function should_check_if_versions_are_equals()
    {
        $v1 = new Version('4.2.30');
        $v2 = new Version('4.2.30');

        $comparator = new Comparator;
        $this->assertTrue($comparator->isEqual($v1, $v2));
    }

    /**
    * @test
    */ 
    public function should_returns_dependecies_diff_empty()
    {
        $project = new Project('Phartitura', new Version('1.0.0'));
        $project->addDependency(new Project('dep1', new Version('1.2.0')));
        $project->addDependency(new Project('dep2', new Version('3.2.1')));
        $project->addDependency(new Project('dep3', new Version('2.0.1')));

        $comparator = new Comparator;
        $diff = $comparator->diff($project, $project);

        $this->assertEquals(['dependencies' => [
            'dep1' => ['1.2.0' => '1.2.0'],
            'dep2' => ['3.2.1' => '3.2.1'],
            'dep3' => ['2.0.1' => '2.0.1'],            
        ]], $diff);
    }

    /**
    * @test
    */ 
    public function should_returns_dependecies_diff_with_different_names()
    {
        $project = new Project('Cloudson/Phartitura', new Version('1.0.0'));
        $project->addDependency(new Project('dep1', new Version('1.2.0')));
        $project->addDependency(new Project('dep2', new Version('3.2.0')));
        
        $project2 = new Project('Cloudson/Phartitura', new Version('1.0.0'));
        $project2->addDependency(new Project('dep2', new Version('3.2.1')));

        $comparator = new Comparator;
        $diff = $comparator->diff($project, $project2);

        $this->assertEquals([
            'dependencies' => [
                'dep1' => ['1.2.0' => ''],
                'dep2' => ['3.2.0' => '3.2.1']
            ]
        ], $diff);
    }
}