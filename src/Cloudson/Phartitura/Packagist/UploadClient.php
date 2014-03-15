<?php

namespace Cloudson\Phartitura\Packagist;

use Cloudson\Phartitura\ClientProjectInterface;

class UploadClient implements ClientProjectInterface
{

    private $converter;

    private $file;

    public function __construct(JsonConverter $converter)
    {
        $this->converter = $converter;
        
    }

    public function setFile(\SplFileInfo $file)
    {
        $this->file = $file;
    }

    public function ping($projectName)
    {
        return 200;
    }

    public function getProject($name)
    {
        $content = file_get_contents((string)$this->file);
        
        return $this->converter->convert($content);
    }
}