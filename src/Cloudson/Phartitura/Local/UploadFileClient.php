<?php

namespace Cloudson\Phartitura\Local;

use Cloudson\Phartitura\Curl\ClientAdapter;
use Cloudson\Phartitura\Packagist\JsonConverter;

class UploadFileClient implements ClientAdapter
{

    private $converter;

    private $file;

    private $client;

    private $checksum;

    public function __construct(JsonConverter $converter)
    {
        $this->converter = $converter;
    }

    public function setFile(\SplFileInfo $file)
    {
        $this->file = $file;
        $contents = file_get_contents($this->file);
        $json = json_decode($contents, true);
        $checksum = '';
        if ($json) {
            $checksum = $this->getChecksum($json['name']);
        }

        $this->checksum = $checksum;
    }

    private function getChecksum($value)
    {
        return $value;
    }

    public function setSubClient(ClientAdapter $client)
    {   
        $this->client = $client;
    }

    public function head($relativeURI)
    {
        return new FileResponse('');
    }

    public function setSecure($isSecure){}

    public function get($relativeURI)
    {
        if ($this->client) {
            if (false === strpos($relativeURI, $this->checksum)) {
                return $this->client->get($relativeURI);
            }
        }
 
        $content = file_get_contents((string)$this->file);
        $json = $this->converter->convert($content);
        
        return new FileResponse($json);
    }

    public static function getLocalName($json) 
    {
        $json = json_decode($json, true);

        return explode('/', $json['name'])[1];
    }

    public static function getLocalUser($json)
    {
        $json = json_decode($json, true);

        return explode('/', $json['name'])[0];
    }
}