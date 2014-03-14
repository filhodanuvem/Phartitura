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
        $found = $converter->convert($composerjson);
        $expected = [
            'name' => 'cloudson/Gandalf',
            'description' => 'blah!',
        ]; 

        $this->assertEquals($expected, $found);
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
}