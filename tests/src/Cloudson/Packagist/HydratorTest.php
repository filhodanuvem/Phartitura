<?php

namespace Cloudson\Phartitura\Packagist;

use Cloudson\Phartitura\Project;

class HydratorTest extends \PHPUnit_Framework_TestCase
{
    /**
    * @test
    * @expectedException \BadMethodCallException
    */ 
    public function should_throw_error_with_invalid_json()
    {   
        $json = [];

        $project = new Project\Project('cloudson/gandalf', new Project\Version('dev-master'));

        $hydrator = new Hydrator(new Project\Comparator);
        $hydrator->hydrate($json, $project);
    }

    /**
    * @test
    */ 
    public function should_hydrate_simple_project()
    {
        $json = [
            'package' => [
                'name' => 'cloudson/gandalf',
                'description' => 'A crazy php library that handles functions',
                'versions' =>  [
                   '0.7.0' => [ 
                        'name' => 'cloudson/gandalf',
                        'description' => 'A crazy php library that handles functions',
                        'version' => '0.7.0',
                        'time' => '2014-01-26T00:12:17+00:00'
                    ]
                ]
            ]
        ];

        $project = new Project\Project('undefined/undefined', new Project\Version('0.0.0'));

        $hydrator = new Hydrator(new Project\Comparator);
        $hydrator->hydrate($json, $project);

        $this->assertEquals('cloudson/gandalf', $project->getName());
        $this->assertEquals('0.7.0', (string)$project->getVersion());
        $this->assertEquals('A crazy php library that handles functions', $project->getDescription());
    }

    /**
    * @test
    */
    public function should_hydrate_with_latest_version()
    {
        $json = [
            'package' => [
                'name' => 'cloudson/gandalf',
                'description' => 'A crazy php library that handles functions',
                'versions' =>  [
                   '1.42.0' => [ 
                        'name' => 'cloudson/gandalf',
                        'description' => 'A crazy php library that handles functions',
                        'version' => '1.42.0',
                        'time' => '2012-12-01T00:12:17+00:00'
                    ],
                    'dev-master' => [
                        'name' => 'cloudson/gandalf',
                        'description' => 'A crazy php library that handles functions',
                        'version' => 'dev-master',
                        'time' => '2014-01-29T00:12:17+00:00'  
                    ],
                    '1.40.0' => [ 
                        'name' => 'cloudson/gandalf',
                        'description' => 'A crazy php library that handles functions',
                        'version' => '1.40.0',
                        'time' => '2012-12-14T00:00:00+00:00'
                    ]
                ]
            ]
        ];

        $project = new Project\Project('undefined/undefined', new Project\Version('0.0.0'));

        $hydrator = new Hydrator(new Project\Comparator);
        $hydrator->hydrate($json, $project);

        $this->assertEquals('1.40.0', (string)$project->getVersion());
    }

    /**
    * @test
    */ 
    public function should_hydrate_with_specific_version()
    {
        $json = [
            'package' => [
                'name' => 'cloudson/gandalf',
                'description' => 'A crazy php library that handles functions',
                'versions' =>  [
                   '1.42.0' => [ 
                        'name' => 'cloudson/gandalf',
                        'description' => 'A crazy php library that handles functions',
                        'version' => '1.42.0',
                        'time' => '2012-12-01T00:12:17+00:00'
                    ],
                    'dev-master' => [
                        'name' => 'cloudson/gandalf',
                        'description' => 'A crazy php library that handles functions',
                        'version' => 'dev-master',
                        'time' => '2014-01-29T00:12:17+00:00'  
                    ],
                    '1.40.0' => [ 
                        'name' => 'cloudson/gandalf',
                        'description' => 'A crazy php library that handles functions',
                        'version' => '1.40.0',
                        'time' => '2012-12-14T00:00:00+00:00'
                    ]
                ]
            ]
        ];

        $project = new Project\Project('undefined/undefined', new Project\Version('0.0.0'));

        $hydrator = new Hydrator(new Project\Comparator, new Project\Version('1.42.0'));
        $hydrator->hydrate($json, $project);

        $this->assertEquals('1.42.0', (string)$project->getVersion());
    }

    /**
    * @test
    * @expectedException Cloudson\Phartitura\Project\Exception\VersionNotFoundException
    */ 
    public function should_throw_error_if_trying_hydrate_with_version_not_found()
    {
        $json = [
            'package' => [
                'name' => 'cloudson/gandalf',
                'description' => 'A crazy php library that handles functions',
                'versions' =>  [
                   '1.42.0' => [ 
                        'name' => 'cloudson/gandalf',
                        'description' => 'A crazy php library that handles functions',
                        'version' => '1.42.0',
                        'time' => '2012-12-01T00:12:17+00:00'
                    ],
                    'dev-master' => [
                        'name' => 'cloudson/gandalf',
                        'description' => 'A crazy php library that handles functions',
                        'version' => 'dev-master',
                        'time' => '2014-01-29T00:12:17+00:00'  
                    ],
                    '1.40.0' => [ 
                        'name' => 'cloudson/gandalf',
                        'description' => 'A crazy php library that handles functions',
                        'version' => '1.40.0',
                        'time' => '2012-12-14T00:00:00+00:00'
                    ]
                ]
            ]
        ];

        $project = new Project\Project('undefined/undefined', new Project\Version('0.0.0'));

        $hydrator = new Hydrator(new Project\Comparator, new Project\Version('6.6.6'));
        $hydrator->hydrate($json, $project);
    }

}