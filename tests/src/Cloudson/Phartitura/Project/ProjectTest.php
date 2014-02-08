<?php

namespace Cloudson\Phartitura\Project; 

use Cloudson\Phartitura\Project\Version\Version; 

class ProjectTest extends \PHPUnit_Framework_TestCase
{
    /**
    * @test
    */ 
    public function should_returns_zero_dependencies()
    {
        $project  = new Project('cloudson/foo', new Version('5.42.3')); 
        $dependencies = $project->getDependencies();

        $this->assertTrue(count($dependencies) === 0);
    }

    /**
    * @test
    */ 
    public function should_returns_any_dependencies()
    {
        $projectNames = [
            'Respect/Rest',
            'Twig/twig',
            'guzzle/guzzle',
        ];

        $project = new Project('cloudson/bar', new Version('5.42.3'));
        $project->addDependency(new Dependency($projectNames[0], new Version('5.42.3')));
        $project->addDependency(new Dependency($projectNames[1], new Version('5.42.3')));
        $project->addDependency(new Dependency($projectNames[2], new Version('5.42.3')));

        $dependencies = $project->getDependencies();
        $this->assertTrue(count($dependencies) === 3);

        foreach ($dependencies as $key => $dependency) {
            $this->assertEquals($projectNames[$key], $dependency->getName());
        }
    }

    /**
    * @test
    */ 
    public function should_be_countable_and_transvarsable()
    {
        $projectNames = [
            'Respect/Rest',
            'Twig/twig',
            'guzzle/guzzle',
        ];

        $project = new Project('cloudson/bar', new Version('5.42.3'));
        $project->addDependency(new Dependency($projectNames[0], new Version('5.42.3')));
        $project->addDependency(new Dependency($projectNames[1], new Version('5.42.3')));
        $project->addDependency(new Dependency($projectNames[2], new Version('5.42.3')));

        $this->assertTrue(count($project) === 3);

        foreach ($project as $key => $dependency) {
            $this->assertEquals($projectNames[$key], $dependency->getName());
        }
    }

    /**
    * @test
    * @dataProvider getDependenciesWithInvalidNames
    * @expectedException \Cloudson\Phartitura\Project\Exception\InvalidNameException
    */ 
    public function should_throw_error_with_dependencies_out_of_pattern($name)
    {
        new Project($name, new Version('0.0.0'));
    }

    public function getDependenciesWithInvalidNames()
    {
        return [
            ['php'],
            [0], 
        ];
    }
}