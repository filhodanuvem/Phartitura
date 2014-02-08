<?php

namespace Cloudson\Phartitura\Service;

use Cloudson\Phartitura\ClientProjectInterface;
use Cloudson\Phartitura\Packagist\Client; 
use Cloudson\Phartitura\Packagist\Hydrator;
use Cloudson\Phartitura\Project\Version\Comparator\ExactVersion;
use Cloudson\Phartitura\Project\Version\Comparator\RangeVersion;
use Cloudson\Phartitura\Project\Version\Comparator\TildeVersion;
use Cloudson\Phartitura\Project\Version\Comparator\WildCardVersion;
use Cloudson\Phartitura\Project\Version\Comparator;
use Guzzle\Http\Client as Guzzle; 
use Cloudson\Phartitura\Curl\GuzzleAdapter;

class ProjectService 
{

    private $client; 

    public function __construct()
    {
        $comparator = new Comparator; 
        $comparator->addComparator(new ExactVersion);
        $comparator->addComparator(new TildeVersion);
        $comparator->addComparator(new RangeVersion);
        $comparator->addComparator(new WildCardVersion);

        $g = new Guzzle('http://'.Client::BASE);
        $gAdapter = new GuzzleAdapter($g);

        $hydrator = new Hydrator($comparator);
        $this->client = new Client($gAdapter, $hydrator);
    }

    public function getProject($username, $projectName, $version = null)
    {
        $project = $this->client->getProject(
            sprintf('%s/%s', $username, $projectName),
            $version
        );

        return $project; 
    }

}