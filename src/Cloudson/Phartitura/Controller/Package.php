<?php

namespace Cloudson\Phartitura\Controller;

use Cloudson\Phartitura\Service\ProjectService;
use Cloudson\Phartitura\Project\Exception\ProjectNotFoundException;
use Cloudson\Phartitura\Project\Exception\VersionNotFoundException;
use Cloudson\Phartitura\Project\Exception\InvalidNameException; 

class Package 
{
    public static function __callStatic($name, $args)
    {   
        $function = null;
        switch ($name) {
            case 'get': 
                $function = function ($user, $packageName, $version =null) use ($args){
                    $container = $args[0];
                    $service = new ProjectService($container->redisAdapter);
                    try {
                        $project = $service->getProject($user, $packageName, str_replace('-', '.', $version));
                    } catch (ProjectNotFoundException $e) {
                        $container->monolog->notice($e->getMessage());
                        http_response_code(404);
                        return [
                            '_view' => 'project_404.html',
                            'name' => $e->getProjectName(),
                            'label' => $e->getProjectName() == sprintf('%s/%s', $user, $packageName) ? 'project' : 'dependency',
                        ];
                    } catch (InvalidNameException $e) {
                        $container->monolog->notice($e->getMessage());
                        http_response_code(404);
                        $name = sprintf('%s/%s', $user, $packageName);
                        return [
                            '_view' => 'project_404.html',
                            'name' => $name,
                            'label' => 'page',
                        ];
                    } catch (VersionNotFoundException $e) {
                        http_response_code(502);
                        $container->monolog->error($e->getMessage());
                        return [
                            '_view' => '500.html',
                        ];
                    } catch (\Exception $e) {
                        http_response_code(500);
                        $container->monolog->error($e->getMessage());
                        return [
                            '_view' => '500.html',
                        ];
                    }

                    return [
                        '_view' => 'project_view.html',
                        'project' => $project,
                    ];
                };
            break;
        }

        return $function;

    }
}

