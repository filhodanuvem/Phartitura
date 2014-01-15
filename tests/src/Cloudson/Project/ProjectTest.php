<?php

namespace Cloudson\Phartitura\Project; 

class ProjectTest extends \PHPUnit_Framework_TestCase
{
    /**
    * @test
    */ 
    public function should_returns_zero_dependencies()
    {
        $project  = new Project('cloudson/foo'); 
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

        $project = new Project('cloudson/bar');
        $project->addDependency(new Project($projectNames[0]));
        $project->addDependency(new Project($projectNames[1]));
        $project->addDependency(new Project($projectNames[2]));

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

        $project = new Project('cloudson/bar');
        $project->addDependency(new Project($projectNames[0]));
        $project->addDependency(new Project($projectNames[1]));
        $project->addDependency(new Project($projectNames[2]));

        $this->assertTrue(count($project) === 3);

        foreach ($project as $key => $dependency) {
            $this->assertEquals($projectNames[$key], $dependency->getName());
        }
    }
}