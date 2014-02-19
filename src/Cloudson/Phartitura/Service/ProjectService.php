<?php

namespace Cloudson\Phartitura\Service;

use Cloudson\Phartitura\ClientProjectInterface;
use Cloudson\Phartitura\Packagist\Client; 
use Cloudson\Phartitura\Packagist\Hydrator;
use Cloudson\Phartitura\Project\Version\Comparator\ComparatorBuilder;
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
        
        $builder = new ComparatorBuilder;
        $builder->withExactVersion()->withRangeVersion()->withTildeVersion()->WildCardVersion();

        $hydrator = new Hydrator($builder, '6.6.6');

        $g = new Guzzle('http://'.Client::BASE);
        
        $gAdapter = new GuzzleAdapter($g);
        $hydrator = new Hydrator($builder);
        
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