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

        $expected = ['dependencies' => [
            'dep1' => ['1.2.0' , '1.2.0'],
            'dep2' => ['3.2.1' , '3.2.1'],
            'dep3' => ['2.0.1' , '2.0.1'],            
        ]];

        foreach ($expected['dependencies'] as $name => $diffString) {
            $this->assertTrue(array_key_exists($name, $diff['dependencies']));
            $this->assertEquals($diffString , iterator_to_array($diff['dependencies'][$name]));  
        }
    }

    /**
    * @test
    */ 
    public function should_returns_dependecies_diff_with_different_vendors()
    {
        $project = new Project('Cloudson/Phartitura', new Version('1.0.0'));
        $project->addDependency(new Project('dep1', new Version('1.2.0')));
        $project->addDependency(new Project('dep2', new Version('3.2.0')));
        
        $project2 = new Project('Cloudson/Phartitura', new Version('1.0.0'));
        $project2->addDependency(new Project('dep2', new Version('3.2.1')));

        $comparator = new Comparator;
        $diff = $comparator->diff($project, $project2);

        $expected = ['dependencies' => [
            'dep1' => ['1.2.0' , ''],
            'dep2' => ['3.2.0' , '3.2.1']
        ]];

        foreach ($expected['dependencies'] as $name => $diffString) {
            $this->assertTrue(array_key_exists($name, $diff['dependencies']));
            $this->assertEquals($diffString , iterator_to_array($diff['dependencies'][$name]));  
        }
    }

    /** 
    * @test
    * @expectedException \InvalidArgumentException
    **/  
    public function should_throw_errors_if_projets_are_not_same()
    {
        $project  = new Project('Cloudson/Phartitura', new Version('1.0.0'));
        $project2 = new Project('Cloudson/Bocejo', new Version('1.0.0'));

        $comparator = new Comparator; 
        $comparator->diff($project, $project2);
    }
}