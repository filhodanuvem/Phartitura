<?php

namespace Cloudson\Phartitura\Cache;

class RedisAdapter implements CacheAdapterInterface
{
    const LIST_LATEST_PROJECTS = 'phtr_latest_project_list';
    const LIST_LATEST_PROJECTS_LIMIT = 8;
    const TIMEOUT = 60000;

    private $client; 

    private $defaultTimeout; 

    private $latestProjectsListLimit;

    public function __construct(\Redis $client, $timeout = self::TIMEOUT, $latestProjectsListLimit = self::LIST_LATEST_PROJECTS_LIMIT)
    {
        $this->client = $client;
        $this->defaultTimeout = $timeout;
        $this->latestProjectsListLimit = $latestProjectsListLimit;
    }

    public function getProject($projectName)
    {
        return $this->client->get($projectName)?: null;
    }

    public function hasProject($projectName)
    {
        return $this->client->exists($projectName);
    }

    public function saveProject($projectName, $json, $onDisk = false)
    {
        $this->client->set($projectName, $json);
        $this->client->setTimeout($projectName, $this->defaultTimeout);
        if ($onDisk) {
            $this->client->save();
        }
    }

    public function saveView($projectName)
    {
        $list = $this->getViews();
        if (false !== array_search($projectName, $list)) {
            $this->client->lrem(self::LIST_LATEST_PROJECTS, $projectName, -1);
        }
        $this->client->lpush(self::LIST_LATEST_PROJECTS, $projectName);
    }

    public function getViews()
    {
        return $this->client->lrange(self::LIST_LATEST_PROJECTS, 0 , $this->latestProjectsListLimit - 1);
    }
}