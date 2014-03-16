<?php

namespace Cloudson\Phartitura\Packagist;

class JsonConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
    * @test
    * @expectedException \InvalidArgumentException
    */ 
    public function should_throw_exception_with_empty_string()
    {
        $composerjson = "";

        $converter = new JsonConverter;
        $converter->convert($composerjson);
    }

    /**
    * @test
    * @expectedException \InvalidArgumentException
    */ 
    public function should_throw_exception_with_empty_json()
    {
        $composerjson = "{}";

        $converter = new JsonConverter;
        $converter->convert($composerjson);
    }

    /**
    * @test
    */ 
    public function should_convert_a_basic_composer_json()
    {
        $composerjson = <<<JSON
{
    "name": "cloudson/Gandalf",
    "description" : "blah!"
}
JSON;
        $converter = new JsonConverter;
        $found = json_decode($converter->convert($composerjson),true)["package"];
        $expected = [
            'name' => 'cloudson/Gandalf',
            'description' => 'blah!',
        ]; 

        $this->assertEquals($expected['name'], $found['name']);
        $this->assertEquals($expected['description'], $found['description']);
    }

    /** 
    * @test
    * @dataProvider prodive_invalid_json
    * @expectedException \Cloudson\Phartitura\Project\Exception\InvalidJsonException
    */ 
    public function should_throw_exception_with_invalid_json($jsoninvalid)
    {
        $converter = new JsonConverter;
        $converter->convert($jsoninvalid);
    }

    public function prodive_invalid_json()
    {
        return [
            ['{"name":"foo/bar", }'],
            ['{ name: "foo/bar"}'],
            ['{ "name": foo ']
        ];
    }

    /** 
    *  @test
    */
    public function should_set_devmaster_without_version()
    {
        $json = <<<JSON
{
    "name" : "foo/bar",
    "description" : "blah!",
    "dependencies": []
}
JSON;
        $converter = new JsonConverter;
        $found = json_decode($converter->convert($json),true)["package"];
        $expected = 'dev-master';

        $this->assertEquals($expected, $found['versions']['dev-master']['version']);
    }


    /**
    * @test
    */ 
    public function should_set_version_correctly()
    {
        $json = <<<JSON
{
    "name" : "foo/bar",
    "description" : "blah!",
    "version" : "2.1.0"
}
JSON;

        $converter = new JsonConverter;
        $found = json_decode($converter->convert($json),true)["package"];
        $expected = "2.1.0";

        $this->assertEquals($expected, $found['versions']["2.1.0"]['version']);
        $this->assertEquals(1, count($found['versions']));
    }

    /**
    * @test
    */ 
    public function should_use_require()
    {
        $json = <<<JSON
{
    "name" : "foo/bar",
    "description" : "blah!",
    "require": {
        "bar/baz": "2.1.0"
    }
}        
JSON;
        $converter = new JsonConverter;
        $found = json_decode($converter->convert($json),true)["package"];
        $expected = [
            "bar/baz" => "2.1.0"
        ];

        $this->assertEquals($expected, $found['versions']['dev-master']['require']);
    }

    /**
    * @test
    */ 
    public function should_use_require_and_replace()
    {
        $json = <<<JSON
{
    "name" : "foo/bar",
    "description" : "blah!",
    "require": {
        "bar/baz": "2.1.0"
    },
    "replace" : {
        "cloudson/gandalf":"self.version"
    }
}        
JSON;
        $converter = new JsonConverter;
        $found = json_decode($converter->convert($json),true)["package"];
        $expectedRequire = [
            "bar/baz" => "2.1.0"
        ];
        $expectedReplace = [
            "cloudson/gandalf" => "self.version"
        ];

        $this->assertEquals($expectedRequire, $found['versions']['dev-master']['require']);
        $this->assertEquals($expectedReplace, $found['versions']['dev-master']['replace']);
    }


    /**
    * @test
    */ 
    public function should_always_set_timestamp()
    {
        $json = <<<JSON
{
    "name" : "foo/bar",
    "description" : "blah!",
    "require": {
        "bar/baz": "2.1.0"
    }
}
JSON;
        $converter = new JsonConverter;
        $found = json_decode($converter->convert($json),true)["package"];
        
        $this->assertNotNull($found['versions']['dev-master']['time']);

    }
}