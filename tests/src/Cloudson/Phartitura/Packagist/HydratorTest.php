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

        $project = new Project\Project('cloudson/gandalf', new Project\Version\Version('dev-master'));

        $hydrator = new Hydrator(new Project\Version\Comparator\ExactVersion);
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

        $project = new Project\Project('undefined/undefined', new Project\Version\Version('0.0.0'));

        $hydrator = new Hydrator(new Project\Version\Comparator\ExactVersion, '0.7.0');
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

        $project = new Project\Project('undefined/undefined', new Project\Version\Version('0.0.0'));

        $hydrator = new Hydrator(new Project\Version\Comparator\WildCardVersion, '*');
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

        $project = new Project\Project('undefined/undefined', new Project\Version\Version('0.0.0'));

        $hydrator = new Hydrator(new Project\Version\Comparator\ExactVersion, '1.42.0');
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

        $project = new Project\Project('undefined/undefined', new Project\Version\Version('0.0.0'));

        $hydrator = new Hydrator(new Project\Version\Comparator\ExactVersion, '6.6.6');
        $hydrator->hydrate($json, $project);
    }

    /**
    * @test
    * @dataProvider getRulesAndFoundsUnilateral
    */ 
    public function should_hydrate_a_project_using_unilateral_range($rule, $expected)
    {
        $json = $this->getJson();
        $project = new Project\Project('undefined/undefined', new Project\Version\Version('0.0.0'));

        $comparator = new Project\Version\Comparator;
        $comparator->addComparator(new Project\Version\Comparator\ExactVersion);
        $comparator->addComparator(new Project\Version\Comparator\TildeVersion);
        $comparator->addComparator(new Project\Version\Comparator\RangeVersion);

        $hydrator = new Hydrator($comparator, $rule);
        $hydrator->hydrate($json, $project);

        $this->assertEquals($expected, (string)$project->getVersion());

    }

    public function getRulesAndFoundsUnilateral() 
    {
        return [
            ['>=0.2.0', '3.3.0'],
            ['< 0.2.0','0.0.1']
        ];  
    }

    /**
    * @test
    * @dataProvider getRulesAndFoundsBilateral
    */ 
    public function should_hydrate_a_project_using_bilateral_range($rule, $expected)
    {
        $json = $this->getJson();
        $project = new Project\Project('undefined/undefined', new Project\Version\Version('0.0.0'));

        $comparator = new Project\Version\Comparator;
        $comparator->addComparator(new Project\Version\Comparator\ExactVersion);
        $comparator->addComparator(new Project\Version\Comparator\TildeVersion);
        $comparator->addComparator(new Project\Version\Comparator\RangeVersion);

        $hydrator = new Hydrator($comparator, $rule);
        $hydrator->hydrate($json, $project);

        $this->assertEquals($expected, (string)$project->getVersion());
    }

    public function getRulesAndFoundsBilateral()
    {
        return [
            ['>=0.0.1, <0.1.0', '0.0.1'],
            ['>0.0.1, <5.0.0', '3.3.0'],
            ['>0.0.1, <3.0.0', '0.2.0'],
        ];
    }

    private function getJson()
    {
        return [
            'package' => [
                'name' => 'cloudson/phartitura',
                'description' => 'A crazy php library that handles functions',
                'versions' =>  [
                   '0.0.1' => [ 
                        'name' => 'cloudson/phartitura',
                        'description' => '',
                        'version' => '0.0.1',
                        'time' => '2014-01-23T00:12:17+00:00'
                    ],
                    '0.2.0' => [ 
                        'name' => 'cloudson/phartitura',
                        'description' => '',
                        'version' => '0.2.0',
                        'time' => '2014-01-24T00:12:17+00:00'
                    ],
                    '3.3.0' => [ 
                        'name' => 'cloudson/phartitura',
                        'description' => '',
                        'version' => '3.3.0',
                        'time' => '2014-01-25T00:12:17+00:00'
                    ],
                ],
            ]
        ];
    }

}