<?php

namespace Cloudson\Phartitura\Packagist;

use Cloudson\Phartitura\Project\Project;

class Renderer
{
    public function render(Project $project, $level = 0)
    {
        $this->output(sprintf("%s:%s", $project->getName(), $project->getVersion()), $level);
        $dependencies = $project->getDependencies();
        foreach ($dependencies as $d) {
            $this->render($d, $level + 1);
        }
    }

    private function output($text, $level = 0)
    {
        for ($i=0; $i < $level; $i++) { 
            echo sprintf("___");
        }
        echo sprintf("%s<br>", $text);
    }

}