<?php

namespace Cloudson\Phartitura\Packagist;

use Cloudson\Phartitura\Project\Exception\InvalidJsonException;

class JsonConverter
{
    /** 
    * @var $json string composer.json content
    * @return array formatted vale simulating a response from packagist
    */ 
    public function convert($json)
    {

        if (empty($json)) {
            throw new \InvalidArgumentException("Expected a json");
        }
        $json = str_replace('\\', '\\\\', $json);
        $data = json_decode($json, true);
        $jsonLastError = json_last_error();
        if ($jsonLastError) {
            throw new InvalidJsonException("Json bad formated", $jsonLastError);
        }

        
        if (!$data) {
            throw new \InvalidArgumentException("Expected a json not empty");
        }

        return $data;
    }
}