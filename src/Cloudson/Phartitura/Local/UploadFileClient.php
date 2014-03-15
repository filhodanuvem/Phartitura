<?php

namespace Cloudson\Phartitura\Local;

use Cloudson\Phartitura\Curl\ClientAdapter;
use Cloudson\Phartitura\Packagist\JsonConverter;

class UploadFileClient implements ClientAdapter
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

    public function head($relativeURI){}
    public function setSecure($isSecure){}

    public function get($relativeURI)
    {
        $content = file_get_contents((string)$this->file);

        return $this->converter->convert($content);
    }
}