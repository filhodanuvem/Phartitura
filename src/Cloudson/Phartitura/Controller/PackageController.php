<?php

namespace Cloudson\Phartitura\Controller;

use Respect\Rest\Routable;

use Cloudson\Phartitura\Service\ProjectService;
use Cloudson\Phartitura\Packagist\Renderer;
use Cloudson\Phartitura\Project\Exception\ProjectNotFoundException;
use Cloudson\Phartitura\Project\Exception\VersionNotFoundException;

class PackageController implements Routable
{
    public function get($user, $packageName, $version =null)
    {   
        
        $service = new ProjectService;
        try {
            $project = $service->getProject($user, $packageName, str_replace('-', '.', $version));
        } catch (ProjectNotFoundException $e) {
            http_response_code(404);
            return [
                '_view' => 'project_404.html',
                'name' => sprintf('%s/%s', $user, $packageName),
            ];
        } catch (VersionNotFoundException $e) {
            trigger_error($e->getMessage());
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