<?php

namespace Cloudson\Phartitura\Controller;

use Respect\Rest\Routable;

use Cloudson\Phartitura\Service\ProjectService;
use Cloudson\Phartitura\Packagist\Renderer;
use Cloudson\Phartitura\Project\Exception\ProjectNotFoundException;
use Cloudson\Phartitura\Project\Exception\VersionNotFoundException;

class PackageController implements Routable
{
    private $container ;

    public function __construct($c)
    {
        $this->container = $c;
    }

    public function get($user, $packageName, $version =null)
    {   

        $service = new ProjectService($this->container->redisAdapter);
        try {
            $project = $service->getProject($user, $packageName, str_replace('-', '.', $version));
        } catch (ProjectNotFoundException $e) {
            $this->container->monolog->notice($e->getMessage());
            http_response_code(404);
            return [
                '_view' => 'project_404.html',
                'name' => $e->getProjectName(),
                'AProjectWasNotFound' => $e->getProjectName() == sprintf('%s/%s', $user, $packageName),
            ];
        } catch (VersionNotFoundException $e) {
            $this->container->monolog->error($e->getMessage());
            return [
                '_view' => '500.html',
            ];
        } catch (\Exception $e) {
            $this->container->monolog->error($e->getMessage());
            return [
                '_view' => '500.html',
            ];
        }

        return [
            '_view' => 'project_view.html',
            'project' => $project,
        ];
    }
}