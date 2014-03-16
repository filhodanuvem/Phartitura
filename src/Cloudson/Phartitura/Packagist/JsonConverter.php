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

        return json_encode([
            "package" => [
                "name" => $data['name'],
                "description" => $data['description'],
                "versions" => $this->getVersion($data),
            ]
        ]);
    }

    private function getVersion($data)
    {
        $key = array_key_exists('version', $data)?$data['version']:"dev-master";
        $latestVersion = array_key_exists('dependencies', $data)?$data['dependencies']:[];
        $latestVersion = [
            "version" => $key,
            "time" => date('Y-m-d H:i:s'),
            "require" => array_key_exists('require', $data)?$data['require']:[],
            "replace" => array_key_exists('replace', $data)?$data['replace']:[],
        ];

        return [
            $key => $latestVersion
        ];
    }

}