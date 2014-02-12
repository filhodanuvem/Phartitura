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
use Cloudson\Phartitura\Cache\RedisAdapter;

class ProjectService 
{

    private $client; 

    private $mapper;

    private $cacheClient;

    public function __construct(RedisAdapter $cache)
    {
        $comparator = new Comparator; 
        $comparator->addComparator(new ExactVersion);
        $comparator->addComparator(new TildeVersion);
        $comparator->addComparator(new RangeVersion);
        $comparator->addComparator(new WildCardVersion);

        $g = new Guzzle('http://'.Client::BASE);
        
        $gAdapter = new GuzzleAdapter($g);
        $hydrator = new Hydrator($comparator);
        
        $this->client = new Client($gAdapter, $hydrator, $cache);
        $this->cacheClient = $cache;
    }

    public function getProject($username, $projectName, $version = null)
    {
        $key = sprintf('%s/%s', $username, $projectName);

        $project = $this->client->getProject(
            $key,
            $version
        );
        $this->pushOnLatestProjectsList($key);
        return $project; 
    }

    public function pushOnLatestProjectsList($projectName)
    {
        $this->cacheClient->saveView($projectName);
    }

    public function getLatestProjectsList()
    {
        return $this->cacheClient->getViews();
    }

}