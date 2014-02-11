<?php

namespace Cloudson\Phartitura\Cache;

class RedisAdapter implements CacheAdapterInterface
{
    const TIMEOUT = 60000;

    private $client; 

    private $defaultTimeout; 

    public function __construct(\Redis $client, $timeout = self::TIMEOUT)
    {
        $this->client = $client;

        $this->defaultTimeout = $timeout;
    }

    public function getProject($projectName)
    {
        return $this->client->get($projectName)?: null;
    }

    public function hasProject($projectName)
    {
        return $this->exists($projectName);
    }

    public function saveProject($projectName, $json)
    {
        $this->client->set($projectName, $json);
        $this->client->setTimeout($projectName, $this->defaultTimeout);
        $this->client->save();
    }
}