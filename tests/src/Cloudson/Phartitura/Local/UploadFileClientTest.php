<?php

namespace Cloudson\Phartitura\Local;

use Cloudson\Phartitura\Packagist\JsonConverter;

class UploadFileClientTest extends \PHPUnit_Framework_TestCase
{
    /**
    * @test
    * @expectedException \InvalidArgumentException
    */ 
    public function should_throw_error_with_empty_file()
    {
        $json = "";
        $name = sys_get_temp_dir().'/test.json';
        file_put_contents($name, $json);
        $file = new \SplFileInfo($name);

        $client = new UploadFileClient(new JsonConverter);
        $client->setFile($file);
        $client->get('');
        unlink($name);
    }

    /**
    * @test
    */ 
    public function should_get_simple_project()
    {

        $json = <<<JSON
{
    "name" : "foo/bar",
    "description" : "blah!",
    "version" : "2.0.0",
    "require": {
        "bar/baz": "2.1.0"
    },
    "replace" : {
        "cloudson/gandalf":"self.version"
    }
}        
JSON;
        $name = sys_get_temp_dir().'/test.json';
        file_put_contents($name, $json);
        $file = new \SplFileInfo($name);

        $client = new UploadFileClient(new JsonConverter);
        $client->setFile($file);
        $project = $client->get('');
        unlink($name);

        $expected = [
            "name" => "foo/bar",
            "description" => "blah!",
            "versions"  => [
                "2.0.0"  => [
                    "require"  => [
                        "bar/baz"  => "2.1.0"
                    ],
                    "replace"  => [
                        "cloudson/gandalf"  => "self.version"
                    ]
                ]
            ]
        ];
    }
}