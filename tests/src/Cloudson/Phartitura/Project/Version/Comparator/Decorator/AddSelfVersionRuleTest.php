<?php

namespace Cloudson\Phartitura\Project\Version\Comparator\Decorator;

use Cloudson\Phartitura\Project\Version\Comparator;
use Cloudson\Phartitura\Project\Version\Version;
use Cloudson\Phartitura\Project\Project;


class AddSelfVersionRuleTest extends \PHPUnit_Framework_TestCase
{
    /**
    * @test
    */ 
    public function should_add_comparator_discovering_self_version()
    {
        $comparator = new Comparator;
        $project = new Project('respect/relational', new Version('2.3.2'));

        $addSelfVersionRule = new AddSelfVersionRule($project);
        $addSelfVersionRule($comparator);

        $this->assertTrue($comparator->compare(new Version('2.3.2'), 'self.version'));
    }
}